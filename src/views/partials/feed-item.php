<div class="box feed-item" data-id="<?=$data->id;?>">
    <div class="box-body">
        <div class="feed-item-head row mt-20 m-width-20">
            <div class="feed-item-head-photo">
                <a href="<?=$base;?>/perfil/<?=$data->user->id;?>"><img src="<?=$base;?>/media/avatars/<?=$data->user->avatar;?>" /></a>
            </div>
            <div class="feed-item-head-info">
                <a href="<?=$base;?>/perfil/<?=$data->user->id;?>"><span class="fidi-name"><?=$data->user->name;?></span></a>
                <span class="fidi-action"><?php
                switch($data->type) {
                    case 'text':
                        echo 'fez um post';
                        break;
                    case 'photo':
                        echo 'postou uma foto';
                        break;
                }
                ?></span>
                <br/>
                <span class="fidi-date"><?=date('d/m/Y', strtotime($data->created_at));?></span>
            </div>
            <div class="feed-item-head-btn">
                <img src="<?=$base;?>/assets/images/more.png" />
            </div>
        </div>
        <div class="feed-item-body mt-10 m-width-20">
            <?=nl2br($data->body);?>
        </div>
        <div class="feed-item-buttons row mt-20 m-width-20">
            <div class="like-btn <?=($data->liked ? 'on':'');?>"><?=$data->likeCount;?></div>
            <div class="msg-btn"><?=count($data->comments);?></div>
        </div>
        <div class="feed-item-comments">
            
            <div class="feed-item-comments-area">
                <?php foreach($data->comments as $item): ?>
                    <div class="fic-item row m-height-10 m-width-20">
                        <div class="fic-item-photo">
                            <a href="<?=$base;?>/perfil/<?=$item['user']['id'];?>"><img src="<?=$base;?>/media/avatars/<?=$item['user']['avatar'];?>" /></a>
                        </div>
                        <div class="fic-item-info">
                            <a href="<?=$base;?>/perfil/<?=$item['user']['id'];?>"><?=$item['user']['name'];?></a>
                            <?=$item['body'];?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="fic-answer row m-height-10 m-width-20">
                <div class="fic-item-photo">
                    <a href="<?=$base;?>/perfil/<?=$data->user->id;?>"><img src="<?=$base;?>/media/avatars/<?=$loggedUser->avatar;?>" /></a>
                </div>
                <input type="text" class="fic-item-field" placeholder="Escreva um comentário" />
            </div>

        </div>
    </div>
</div>