<?php

use common\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Perfil $model */

$this->title = $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Colaboradores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$status = $model->user->status ?? null;
$role = $model->getRoleName();

$roleLabel = match ($role) {
    'sys_admin' => 'Administrador Global (SysAdmin)',
    'rececionista' => 'Rececionista (Balcão)',
    'equipa_de_limpeza' => 'Equipa de Limpeza (Operacional)',
    'cliente' => 'Cliente (Front-Office)',
    default => $role,
};
?>
<div class="perfil-view container-fluid">
    <div class="row">
        <!-- Cartão Resumo Lateral -->
        <div class="col-md-4">
            <div class="card card-primary card-outline shadow-sm">
                <div class="card-body box-profile text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-circle fa-5x text-secondary"></i>
                    </div>
                    <h3 class="profile-username text-center font-weight-bold"><?= Html::encode($model->nome) ?></h3>
                    <p class="text-muted text-center mb-2">@<?= Html::encode($model->user->username ?? 'N/A') ?></p>

                    <div class="mb-2">
                        <span class="badge badge-primary px-3 py-2">
                            <i class="fas fa-id-badge mr-1"></i> <?= Html::encode($roleLabel) ?>
                        </span>
                    </div>

                    <div class="mb-3">
                        <?php if ($status === User::STATUS_ACTIVE): ?>
                            <span class="badge badge-success px-3 py-2">Conta Ativa</span>
                        <?php elseif ($status === User::STATUS_INACTIVE): ?>
                            <span class="badge badge-warning px-3 py-2">Conta Inativa</span>
                        <?php else: ?>
                            <span class="badge badge-danger px-3 py-2">Conta Desativada (Soft Delete)</span>
                        <?php endif; ?>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-center flex-wrap">
                        <?= Html::a('<i class="fas fa-edit mr-1"></i> Editar', ['update', 'id_perfil' => $model->id_perfil], ['class' => 'btn btn-primary m-1']) ?>

                        <?php if ($status !== User::STATUS_DELETED): ?>
                            <?= Html::a('<i class="fas fa-user-slash mr-1"></i> Desativar', ['delete', 'id_perfil' => $model->id_perfil], [
                                'class' => 'btn btn-danger m-1',
                                'data' => [
                                    'confirm' => 'Tem a certeza que pretende desativar (Soft Delete) este colaborador?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        <?php else: ?>
                            <?= Html::a('<i class="fas fa-undo mr-1"></i> Reativar', ['restore', 'id_perfil' => $model->id_perfil], [
                                'class' => 'btn btn-success m-1',
                                'data' => [
                                    'confirm' => 'Deseja reativar a conta deste colaborador?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        <?php endif; ?>

                        <?= Html::a('<i class="fas fa-list mr-1"></i> Voltar à Lista', ['index'], ['class' => 'btn btn-secondary m-1']) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela de Detalhes Completos -->
        <div class="col-md-8">
            <div class="card card-info card-outline shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-info-circle mr-2 text-info"></i> Ficha Completa do Colaborador
                    </h3>
                </div>
                <div class="card-body p-0">
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-striped table-bordered mb-0'],
                        'attributes' => [
                            'id_perfil',
                            'nome',
                            [
                                'label' => 'Cargo / Perfil (RBAC)',
                                'format' => 'raw',
                                'value' => Html::tag('strong', Html::encode($roleLabel)),
                            ],
                            [
                                'label' => 'Username',
                                'value' => $model->user->username ?? '—',
                            ],
                            [
                                'label' => 'Email',
                                'format' => 'email',
                                'value' => $model->user->email ?? '—',
                            ],
                            [
                                'attribute' => 'nif',
                                'value' => $model->nif ?: 'Não definido',
                            ],
                            'morada',
                            [
                                'label' => 'Registado em',
                                'value' => isset($model->user->created_at) ? date('d/m/Y H:i', $model->user->created_at) : '—',
                            ],
                            [
                                'label' => 'Última atualização',
                                'value' => isset($model->user->updated_at) ? date('d/m/Y H:i', $model->user->updated_at) : '—',
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>