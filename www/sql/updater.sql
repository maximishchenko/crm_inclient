--
-- Установка версии 1.0.2.5
--
INSERT INTO `version` (version) VALUES
('1.0.2.5');

create table client_filters
(
    id          int auto_increment primary key,
    author      int         not null,
    name        varchar(80) not null,
    who_visible varchar(10) not null,
    page_size   int(3)      not null,
    is_files    int(1)      not null,
    is_default  int(1)      not null,
    class_name  varchar(80)     null,
    constraint client_filters_ibfk_1
        foreign key (author) references users (id)
            on update cascade on delete cascade
);

create index author
    on client_filters (author);

create table client_filters_block_type
(
    id   int auto_increment primary key,
    name varchar(10) not null
);

create table client_filters_block_additional_fields
(
    id                           int auto_increment
        primary key,
    client_filters_id            int not null,
    client_filters_block_type_id int not null,
    additional_fields_id         int not null,
    constraint client_filters_block_additional_fields_ibfk_1
        foreign key (client_filters_id) references client_filters (id)
            on update cascade on delete cascade,
    constraint client_filters_block_additional_fields_ibfk_2
        foreign key (additional_fields_id) references additional_fields (id)
            on update cascade on delete cascade,
    constraint client_filters_block_additional_fields_ibfk_3
        foreign key (client_filters_block_type_id) references client_filters_block_type (id)
            on update cascade on delete cascade
);

create index additional_fields_id
    on client_filters_block_additional_fields (additional_fields_id);

create index client_filters_block_type_id
    on client_filters_block_additional_fields (client_filters_block_type_id);

create index client_filters_id
    on client_filters_block_additional_fields (client_filters_id);


create table client_filters_block_info
(
    id                           int auto_increment
        primary key,
    client_filters_id            int              not null,
    client_filters_block_type_id int              not null,
    is_id_client                 int(1) default 0 not null,
    is_last_change               int(1) default 0 not null,
    is_create_date               int(1) default 0 not null,
    is_responsible               int(1) default 0 not null,
    is_step                      int(1) default 0 not null,
    is_option_step               int(1) default 0 not null,
    constraint client_filters_block_info_ibfk_1
        foreign key (client_filters_id) references client_filters (id)
            on update cascade on delete cascade,
    constraint client_filters_block_info_ibfk_2
        foreign key (client_filters_block_type_id) references client_filters_block_type (id)
            on update cascade on delete cascade
);

create index client_filters_block_type_id
    on client_filters_block_info (client_filters_block_type_id);

create index client_filters_id
    on client_filters_block_info (client_filters_id);

create table client_filters_labels
(
    id                int auto_increment
        primary key,
    client_filters_id int not null,
    labels_id         int not null,
    constraint client_filters_labels_ibfk_1
        foreign key (client_filters_id) references client_filters (id)
            on update cascade on delete cascade,
    constraint client_filters_labels_ibfk_2
        foreign key (labels_id) references labels (id)
            on update cascade on delete cascade
);

create index client_filters_id
    on client_filters_labels (client_filters_id);

create index labels_id
    on client_filters_labels (labels_id);

create table client_filters_responsibles
(
    id                int auto_increment
        primary key,
    client_filters_id int not null,
    users_id          int not null,
    constraint client_filters_responsibles_ibfk_1
        foreign key (client_filters_id) references client_filters (id)
            on update cascade on delete cascade,
    constraint client_filters_responsibles_ibfk_2
        foreign key (users_id) references users (id)
            on update cascade on delete cascade
);

create index client_filters_id
    on client_filters_responsibles (client_filters_id);

create index users_id
    on client_filters_responsibles (users_id);

create table client_filters_step_options
(
    id                int auto_increment
        primary key,
    client_filters_id int not null,
    steps_options_id  int null,
    constraint client_filters_step_options_ibfk_1
        foreign key (client_filters_id) references client_filters (id)
            on update cascade on delete cascade,
    constraint client_filters_step_options_ibfk_2
        foreign key (steps_options_id) references steps_options (id)
            on update cascade on delete cascade
);

create index client_filters_id
    on client_filters_step_options (client_filters_id);

create index steps_options_id
    on client_filters_step_options (steps_options_id);

INSERT INTO `client_filters`(`id`, `author`, `name`, `who_visible`, `page_size`, `is_files`, `class_name`) VALUES (1, 1, 'Все контакты', 'all', 30, 0, '');

INSERT INTO `client_filters_block_type`(`id`, `name`) VALUES (1, 'left'), (2, 'right');