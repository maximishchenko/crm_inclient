<?php
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
$apiDomain = ''; // Адрес срм. Пример http://crm.domain.ru
$apiKey = ''; // Ключ API
$adminEmail = ''; // Емейл для уведомлений

if (count($_POST) == 0) {
    header("HTTP/1.0 404 Not Found");
    die;
}
$dataClient = [
    'labels_in_clients' => '', // Метка контакта. В срм /page/settings_labels
    'steps' => '', // Воронка контакта. В срм /page/settings_steps
    'steps_options' => '', // Этап воронки контакта
    'responsable_id' => '', // Ответственный контакта
];
$dataClient = array_merge($dataClient, $_POST);
ksort($dataClient);
$sign = md5(implode('', $dataClient) . $apiKey);
$dataClient['sign'] = $sign;
$ch = curl_init();
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_URL, $apiDomain . '/api/page/CreateLead');
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dataClient));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$html = curl_exec($ch);
curl_close($ch);
$resultClient = json_decode($html, true);
$return = $resultClient;

if ($return['result'] == 'failed') {
    foreach ($return['errors'] as $key => $error) {
        if ($error == 'Unique') {
            unset($return['errors'][$key]);
        }
    }
    if (count($return['errors']) == '') {
        $return['result'] = 'success';
        $return['description'] = 'success';
    }
}


$data = [];
$sign = md5(implode('', $data) . $apiKey);
$data['sign'] = $sign;
$ch = curl_init();
curl_setopt($ch, CURLOPT_POST, 0);
curl_setopt($ch, CURLOPT_URL, $apiDomain . '/api/page/GetAdditionalFields?' . http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$html = curl_exec($ch);
curl_close($ch);
$resultFields = json_decode($html, true);

$data = [];
$sign = md5(implode('', $data) . $apiKey);
$data['sign'] = $sign;
$ch = curl_init();
curl_setopt($ch, CURLOPT_POST, 0);
curl_setopt($ch, CURLOPT_URL, $apiDomain . '/api/page/GetSettings?' . http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$html = curl_exec($ch);
curl_close($ch);
$resultSettings = json_decode($html, true);

if (isset($resultFields['values'])) {
    $unique = false;
    $incorrect = false;
    $required = false;
    $emailMessage = '';
    foreach ($resultFields['values'] as $value) {
        if (isset($_POST[$value['table_name']])) {
            switch ($value['type']) {
                case 'select':
                    $select = json_decode($value['default_value'], true);
                    if (isset($select[$_POST[$value['table_name']]])) {
                        $emailMessage .= $value['name'] . ': ' . $select[$_POST[$value['table_name']]]['optionName'];
                    }
                    break;
                case 'date':
                    $emailMessage .= $value['name'] . ': ' . (is_numeric($_POST[$value['table_name']]) ? date('d.m.Y H:i:s', $_POST[$value['table_name']]) : $_POST[$value['table_name']]);
                    break;
                default:
                    $emailMessage .= $value['name'] . ': ' . $_POST[$value['table_name']];
            }
            if ($resultClient['result'] == 'failed') {
                if (isset($resultClient['errors'][$value['table_name']])) {
                    switch ($resultClient['errors'][$value['table_name']]) {
                        case 'Unique';
                            $emailMessage .= ' (дубль)';
                            $unique = true;
                            break;
                    }
                }
            }
            $emailMessage .=  "<p>";
        }
    }

    if (isset($resultSettings['values']['labels']) && isset($resultSettings['values']['steps']) ) {
        foreach ($resultSettings['values']['labels'] as $value) {
            if ($value['id'] == $dataClient['labels_in_clients']) {
                $emailMessage .= 'Метка: ' . $value['name'] . "<p>";
            }
        }
        foreach ($resultSettings['values']['steps'] as $value) {
            if ($value['id'] == $dataClient['steps']) {
                $emailMessage .= 'Воронка: ' . $value['name'] . "<p>";
                foreach ($value['step_options'] as $option) {
                    if ($dataClient['steps_options'] == $option['weight']) {
                        $emailMessage .= 'Этап: ' . $option['name'] . "<p>";
                    }
                }
            }
        }
    }
    if (isset($resultClient['values']['resp_name'])) {
        $emailMessage .= "Ответственный: {$resultClient['values']['resp_name']}<p>";
    }

    if ($unique) {
        $emailMessage .=  "Контакт не создан в срм, потому что это дубль.<p>";
    }

    $redundantFields = [];
    $emailMessageRedundant = '';
    foreach ($_POST as $key => $value) {
        $keyAddField = array_search($key, array_column($resultFields['values'], 'table_name'));
        if ($keyAddField === false) {
            $redundantFields[] = $key;
            $emailMessageRedundant .= $key . ': ' . $value . "<p>";

        }
    }

    if ($emailMessageRedundant != '') {
        $emailMessage .= 'Таких полей не существует в CRM, но контакт заполнил их в форме: <p>';
        $emailMessage .= $emailMessageRedundant;
    }

    $return['redundantFields'] = $redundantFields;
    $return['result'] = 'success';
    echo json_encode($return);
    $requiredFieldsError = [];

    if ($resultClient['result'] == 'failed') {
        foreach ($resultClient['errors'] as $keyField => $value) {
            if ($value == 'Required') {
                $requiredFieldsError[] = $keyField;
            }
        }
    }

    if ($adminEmail != '' && count($requiredFieldsError) == 0) {
        $data = [
            'email' => $adminEmail,
            'subject' => !$unique ? 'Новый контакт' : 'Дубль контакт',
            'text' => $emailMessage,
        ];
        ksort($data);
        $sign = md5(implode('', $data) . $apiKey);
        $data['sign'] = $sign;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $apiDomain . '/api/page/SendEmail');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $html = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($html, true);
    }
} else {
    $return['result'] = 'success';
    echo json_encode($return);
}