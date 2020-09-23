<?=$render('header', ['loggedUser' => $loggedUser]); ?>

<section class="container main">
        <?=$render('sidebar', ['activeMenu' => 'config']);?>
<section class="feed mt-10">

<div class="container">
    <h1>Configurações</h1>

    <div class="container-input-group">
        <form method="POST" action="<?=$base;?>/config">

        <?php if(!empty($flash)): ?>
            <div class="flash"><?php echo $flash; ?></div> 
        <?php endif ?>

            <div class="input-file">
                <p>Novo Avatar:</p>
                <input type="file" name="avatar" >
            </div>

            <div class="input-file">
                <p>Nova Capa:</p>
                <input type="file" name="cover" >
            </div>


            <div class="input">
                <p>Nome Completo:</p>
                <input type="text" name="name"value="<?=$user->name?>">
            </div>

            <div class="input">
                <p>Data de nascimento:</p>
                <input type="text" name="birthdate" id="birthdate" value="<?=date('d/m/Y', strtotime($user->birthdate))?>"/>
            </div>

            <div class="input">
                <p>E-mail:</p>
                <input type="text" name="email" value="<?=$user->email?>">
            </div>

            <div class="input">
                <p>Cidade:</p>
                <input type="text" name="city" value="<?=$user->city?>">
            </div>

            <div class="input">
                <p>Trabalho:</p>
                <input type="text" name="work" value="<?=$user->work?>">
            </div>

        </div>
        <hr>
        <div class="container-input-group">
            <div class="input">
                <p>Nova Senha:</p>
                <input type="password" name="password">
            </div>

            <div class="input">
                <p>Confirmar Nova Senha:</p>
                <input type="password" name="confirmedPassword">
            </div>
        </div>

    <button class="button" type="submit">Salvar</button>
    </form>

    <script src="https://unpkg.com/imask"></script> <!-- Biblioteca que dá sanitize a data !-->

    <script>
        IMask(
            document.getElementById('birthdate'),
            {
                mask:'00/00/0000'
            }
        );
    </script>
</div>


<?=$render('footer');?>

