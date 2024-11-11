                   <div id='note_<?=$note->id?>' class="note_container"> 
                     <div class="note-div color-<?=$note->color?>">
                        <div class="note-text"><?=nl2br($note->text)?></div>
                        <div class="note-edit-block editNote sel-link" data-id="<?=$note->id?>">
                        <span class="toggle-note-edit " ></span>
                        
                        
                            <div class="multi-popap editNotePopup hide " id="editNote_<?=$note->id?>">
                            <a href="#" class="edit_note"    data-id="<?=$note->id?>"   data-color="<?=$note->color?>" >Изменить</a>
                            <a href="#" class="delete_note"  data-id="<?=$note->id?>">Удалить</a>
                            </div>
                        </div>
                      
                        <div class="note_footer">
                           Создано: <?=$note->user->first_name?> <?=Date('d.m.y', $note->added)?> в  <?=Date('H:i', $note->added)?>
                           <?if (!empty($note->edited)) {?>
                           (изменено 
                           <?if ($note->user->id!=$note->edit_user->id) {?>
                           <?=$note->edit_user->first_name?> 
                           <?}?>
                           <?=Date('d.m.y', $note->edited)?> в  <?=Date('H:i', $note->edited)?>)
                           <?}?>
                        </div>
                    </div>
                  </div>