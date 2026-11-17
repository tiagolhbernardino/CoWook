<?php
/** @var yii\web\View $this */
/** @var array $cidades Array com a lista de cidades recebida do SiteController */
/** @var array $tiposEspaco Array com a lista de tipos de espaço recebida do SiteController */

use yii\helpers\Html;
use yii\helpers\Url;

// Define o título da página web atual
$this->title = 'Hotelier - Home';
?>

<!-- =========================================================================
     BARRA DE TOPO / BOTÃO DE TERMINAR SESSÃO (LOGOUT)
     Aparece apenas se o utilizador estiver com sessão iniciada
     ========================================================================= -->
<div class="container py-3 d-flex justify-content-end" style="position: relative; z-index: 10;">
    <?php if (!Yii::$app->user->isGuest): ?>
        <?= Html::beginForm(['/site/logout'], 'post', ['class' => 'd-inline'])
        . Html::submitButton(
            'Terminar Sessão (' . Yii::$app->user->identity->username . ')',
            ['class' => 'btn btn-outline-danger btn-sm px-4 py-2']
        )
        . Html::endForm();
        ?>
    <?php endif; ?>
</div>
<!-- Fim da Barra de Topo -->


<!-- =========================================================================
     SPINNER DE CARREGAMENTO INICIAL
     Animação visual que aparece enquanto a página está a carregar no browser
     ========================================================================= -->
<div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
        <span class="sr-only">A carregar...</span>
    </div>
</div>
<!-- Fim do Spinner -->


<!-- =========================================================================
     CARROSSEL DE IMAGENS DO TOPO
     Slider rotativo com imagens de destaque e chamadas para ação (*Call to Action*)
     ========================================================================= -->
<div class="container-fluid p-0 mb-5">
    <div id="header-carousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">

            <!-- Slide 1 (Ativo por defeito ao abrir a página) -->
            <div class="carousel-item active">
                <img class="w-100" src="<?= Yii::$app->request->baseUrl ?>/img/carousel-1.jpg" alt="Imagem Carousel 1">
                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <div class="p-3" style="max-width: 700px;">
                        <h6 class="section-title text-white text-uppercase mb-3 animated slideInDown">Luxury Living</h6>
                        <h1 class="display-3 text-white mb-4 animated slideInDown">Discover A Brand Luxurious Hotel</h1>
                        <a href="" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Our Rooms</a>
                        <a href="" class="btn btn-light py-md-3 px-md-5 animated slideInRight">Book A Room</a>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-item">
                <img class="w-100" src="<?= Yii::$app->request->baseUrl ?>/img/carousel-2.jpg" alt="Imagem Carousel 2">
                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <div class="p-3" style="max-width: 700px;">
                        <h6 class="section-title text-white text-uppercase mb-3 animated slideInDown">Luxury Living</h6>
                        <h1 class="display-3 text-white mb-4 animated slideInDown">Discover A Brand Luxurious Hotel</h1>
                        <a href="" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Our Rooms</a>
                        <a href="" class="btn btn-light py-md-3 px-md-5 animated slideInRight">Book A Room</a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Botões de navegação lateral (anterior e seguinte) do carrossel -->
        <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#header-carousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Seguinte</span>
        </button>
    </div>
</div>
<!-- Fim do Carrossel -->


<!-- =========================================================================
     BARRA DE PESQUISA / FILTRO DE RESERVA
     Formulário com campos dinâmicos (Cidades e Tipos de Espaço) e fixo (Nº de Pessoas)
     ========================================================================= -->
<div class="container-fluid booking pb-5 wow fadeIn" data-wow-delay="0.1s" style="margin-top: 30px; position: relative; z-index: 9;">
    <div class="container">
        <div class="bg-white shadow" style="padding: 35px;">

            <!-- Formulário de submissão por método GET para filtragem de resultados -->
            <form action="" method="GET">
                <div class="row g-2 align-items-center">

                    <div class="col-md-10">
                        <div class="row g-2">

                            <!-- 1. SELECT DE CIDADES (Preenchido dinamicamente via PHP com dados do SiteController) -->
                            <div class="col-md-4">
                                <select class="form-select border-0 bg-light px-4" name="cidade" style="height: 55px;" required>
                                    <option value="" selected disabled>Selecione a Cidade...</option>
                                    <?php foreach ($cidades as $cidadeItem): ?>
                                        <option value="<?= Html::encode($cidadeItem) ?>">
                                            <?= Html::encode($cidadeItem) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- 2. SELECT DE TIPO DE ESPAÇO (Preenchido dinamicamente via PHP com dados do SiteController) -->
                            <div class="col-md-4">
                                <select class="form-select border-0 bg-light px-4" name="tipo_espaco" style="height: 55px;" required>
                                    <option value="" selected disabled>Tipo de Espaço...</option>
                                    <?php foreach ($tiposEspaco as $tipoItem): ?>
                                        <option value="<?= Html::encode($tipoItem) ?>">
                                            <?= Html::encode($tipoItem) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- 3. SELECT DE NÚMERO DE PESSOAS (Opções estáticas/fixas) -->
                            <div class="col-md-4">
                                <select class="form-select border-0 bg-light px-4" name="num_pessoas" style="height: 55px;" required>
                                    <option value="" selected disabled>Nº de Pessoas...</option>
                                    <option value="1">1</option>
                                    <option value="2">+2</option>
                                    <option value="4">+4</option>
                                    <option value="8">+8</option>
                                    <option value="16">+16</option>
                                </select>
                            </div>

                        </div>
                    </div>

                    <!-- Botão de submissão da barra de pesquisa -->
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 py-3">PESQUISAR</button>
                    </div>

                </div>
            </form>

        </div>
    </div>
</div>
<!-- Fim da Barra de Pesquisa -->


<!-- =========================================================================
     SECÇÃO "ABOUT US" (Sobre Nós)
     Apresentação institucional contendo texto descritivo, contadores e mosaico de imagens
     ========================================================================= -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-5 align-items-center">

            <!-- Coluna da Esquerda: Textos institucionais e blocos de estatísticas -->
            <div class="col-lg-6">
                <h6 class="section-title text-start text-primary text-uppercase">About Us</h6>
                <h1 class="mb-4">Welcome to <span class="text-primary text-uppercase">Hotelier</span></h1>
                <p class="mb-4">Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit. Aliqu diam amet diam et eos. Clita erat ipsum et lorem et sit, sed stet lorem sit clita duo justo magna dolore erat amet</p>

                <!-- Grelha com 3 contadores numéricos animados -->
                <div class="row g-3 pb-4">
                    <!-- Contador de Quartos/Rooms -->
                    <div class="col-sm-4 wow fadeIn" data-wow-delay="0.1s">
                        <div class="border rounded p-1">
                            <div class="border rounded text-center p-4">
                                <i class="fa fa-hotel fa-2x text-primary mb-2"></i>
                                <h2 class="mb-1" data-toggle="counter-up">1234</h2>
                                <p class="mb-0">Rooms</p>
                            </div>
                        </div>
                    </div>
                    <!-- Contador de Staff -->
                    <div class="col-sm-4 wow fadeIn" data-wow-delay="0.3s">
                        <div class="border rounded p-1">
                            <div class="border rounded text-center p-4">
                                <i class="fa fa-users-cog fa-2x text-primary mb-2"></i>
                                <h2 class="mb-1" data-toggle="counter-up">1234</h2>
                                <p class="mb-0">Staffs</p>
                            </div>
                        </div>
                    </div>
                    <!-- Contador de Clientes -->
                    <div class="col-sm-4 wow fadeIn" data-wow-delay="0.5s">
                        <div class="border rounded p-1">
                            <div class="border rounded text-center p-4">
                                <i class="fa fa-users fa-2x text-primary mb-2"></i>
                                <h2 class="mb-1" data-toggle="counter-up">1234</h2>
                                <p class="mb-0">Clients</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botão de ação secundária para explorar mais -->
                <a class="btn btn-primary py-3 px-5 mt-2" href="">Explore More</a>
            </div>

            <!-- Coluna da Direita: Mosaico decorativo com 4 imagens intercaladas -->
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6 text-end">
                        <img class="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.1s" src="<?= Yii::$app->request->baseUrl ?>/img/about-1.jpg" style="margin-top: 25%;" alt="About 1">
                    </div>
                    <div class="col-6 text-start">
                        <img class="img-fluid rounded w-100 wow zoomIn" data-wow-delay="0.3s" src="<?= Yii::$app->request->baseUrl ?>/img/about-2.jpg" alt="About 2">
                    </div>
                    <div class="col-6 text-end">
                        <img class="img-fluid rounded w-50 wow zoomIn" data-wow-delay="0.5s" src="<?= Yii::$app->request->baseUrl ?>/img/about-3.jpg" alt="About 3">
                    </div>
                    <div class="col-6 text-start">
                        <img class="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.7s" src="<?= Yii::$app->request->baseUrl ?>/img/about-4.jpg" alt="About 4">
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Fim da Secção About -->