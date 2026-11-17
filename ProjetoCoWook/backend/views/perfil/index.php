<?php

use common\models\Perfil;
use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\PerfilSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestão de Colaboradores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="perfil-index container-fluid">

    <div class="card card-primary card-outline shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title font-weight-bold m-0">
                <i class="fas fa-users mr-2 text-primary"></i> Lista de Colaboradores
            </h3>
            <div class="card-tools ml-auto">
                <?= Html::a('<i class="fas fa-user-plus mr-1"></i> Adicionar Colaborador', ['create'], ['class' => 'btn btn-success btn-sm font-weight-bold']) ?>
            </div>
        </div>

        <div class="card-body table-responsive p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-hover table-striped text-nowrap mb-0'],
                'summaryOptions' => ['class' => 'p-3 text-muted'],
                'columns' => [
                    [
                        'attribute' => 'id_perfil',
                        'headerOptions' => ['style' => 'width: 80px;'],
                    ],
                    [
                        'attribute' => 'nome',
                        'format' => 'raw',
                        'value' => function (Perfil $model) {
                            return Html::tag('strong', Html::encode($model->nome));
                        },
                    ],
                    [
                        'attribute' => 'username',
                        'label' => 'Username',
                        'value' => 'user.username',
                    ],
                    [
                        'attribute' => 'email',
                        'label' => 'Email',
                        'format' => 'email',
                        'value' => 'user.email',
                    ],
                    [
                        'attribute' => 'role',
                        'label' => 'Cargo / Role',
                        'format' => 'raw',
                        'filter' => [
                            'sys_admin' => 'Administrador (SysAdmin)',
                            'rececionista' => 'Rececionista',
                            'equipa_de_limpeza' => 'Equipa de Limpeza',
                            'cliente' => 'Cliente',
                        ],
                        'value' => function (Perfil $model) {
                            $role = $model->getRoleName();
                            return match ($role) {
                                'sys_admin' => '<span class="badge badge-dark px-2 py-1"><i class="fas fa-user-shield mr-1"></i> SysAdmin</span>',
                                'rececionista' => '<span class="badge badge-info px-2 py-1"><i class="fas fa-concierge-bell mr-1"></i> Rececionista</span>',
                                'equipa_de_limpeza' => '<span class="badge badge-secondary px-2 py-1"><i class="fas fa-broom mr-1"></i> Equipa de Limpeza</span>',
                                'cliente' => '<span class="badge badge-light border px-2 py-1"><i class="fas fa-user mr-1"></i> Cliente</span>',
                                default => '<span class="badge badge-light border px-2 py-1">Sem Role</span>',
                            };
                        },
                    ],
                    [
                        'attribute' => 'nif',
                        'value' => function (Perfil $model) {
                            return $model->nif ?: '—';
                        },
                    ],
                    [
                        'attribute' => 'status',
                        'label' => 'Estado',
                        'format' => 'raw',
                        'filter' => [
                            User::STATUS_ACTIVE => 'Ativo',
                            User::STATUS_INACTIVE => 'Inativo',
                            User::STATUS_DELETED => 'Eliminado (Soft Delete)',
                        ],
                        'value' => function (Perfil $model) {
                            $status = $model->user->status ?? null;
                            if ($status === User::STATUS_ACTIVE) {
                                return '<span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Ativo</span>';
                            }
                            if ($status === User::STATUS_INACTIVE) {
                                return '<span class="badge badge-warning px-2 py-1"><i class="fas fa-pause-circle mr-1"></i> Inativo</span>';
                            }
                            return '<span class="badge badge-danger px-2 py-1"><i class="fas fa-ban mr-1"></i> Eliminado</span>';
                        },
                    ],
                    [
                        'class' => ActionColumn::class,
                        'header' => 'Ações',
                        'headerOptions' => ['class' => 'text-center', 'style' => 'width: 160px;'],
                        'contentOptions' => ['class' => 'text-center'],
                        'template' => '{view} {update} {delete} {restore}',
                        'urlCreator' => function ($action, Perfil $model) {
                            return Url::toRoute([$action, 'id_perfil' => $model->id_perfil]);
                        },
                        'buttons' => [
                            'view' => function ($url) {
                                return Html::a('<i class="fas fa-eye"></i>', $url, [
                                    'class' => 'btn btn-info btn-sm mr-1',
                                    'title' => 'Ver Detalhes',
                                ]);
                            },
                            'update' => function ($url) {
                                return Html::a('<i class="fas fa-edit"></i>', $url, [
                                    'class' => 'btn btn-primary btn-sm mr-1',
                                    'title' => 'Editar Colaborador',
                                ]);
                            },
                            'delete' => function ($url, Perfil $model) {
                                if (($model->user->status ?? null) === User::STATUS_DELETED) {
                                    return '';
                                }
                                return Html::a('<i class="fas fa-user-slash"></i>', $url, [
                                    'class' => 'btn btn-danger btn-sm',
                                    'title' => 'Desativar (Soft Delete)',
                                    'data-confirm' => 'Tem a certeza que deseja desativar este colaborador?',
                                    'data-method' => 'post',
                                ]);
                            },
                            'restore' => function ($url, Perfil $model) {
                                if (($model->user->status ?? null) !== User::STATUS_DELETED) {
                                    return '';
                                }
                                return Html::a('<i class="fas fa-undo"></i>', $url, [
                                    'class' => 'btn btn-success btn-sm',
                                    'title' => 'Reativar Colaborador',
                                    'data-confirm' => 'Deseja reativar a conta deste colaborador?',
                                    'data-method' => 'post',
                                ]);
                            },
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>

</div>