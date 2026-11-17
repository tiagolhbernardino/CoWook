<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

/**
 * RbacController gere a inicialização e configuração dos Roles (Perfis)
 * e Permissões do sistema CoWook com base na Matriz de Controlo de Acessos oficial.
 */
class RbacController extends Controller
{
    /**
     * Comando executado via consola: php yii rbac/init
     */
    public function actionInit()
    {
        // 1. Obter o gestor de autorização configurado (DbManager na base de dados)
        $auth = Yii::$app->authManager;

        // Limpar todas as regras, atribuições e itens anteriores para evitar duplicados ou conflitos
        $auth->removeAll();

        // =========================================================
        // 2. CRIAR AS PERMISSÕES (Exatamente conforme a Matriz)
        // =========================================================

        // --- Módulo: Front-Office / App[cite: 7] ---
        $pesquisarEspacos = $auth->createPermission('pesquisarEspacos');
        $pesquisarEspacos->description = 'Pesquisar espaços (Filtros por local/data/hora)[cite: 7]';
        $auth->add($pesquisarEspacos);

        $calcularPreco = $auth->createPermission('calcularPreco');
        $calcularPreco->description = 'Calcular preço (Cálculo automático)[cite: 7]';
        $auth->add($calcularPreco);

        $efetuarReservaPagamento = $auth->createPermission('efetuarReservaPagamento');
        $efetuarReservaPagamento->description = 'Efetuar reserva e pagamento (Confirmação)[cite: 7]';
        $auth->add($efetuarReservaPagamento);

        $cancelarReserva = $auth->createPermission('cancelarReserva');
        $cancelarReserva->description = 'Cancelar reserva (Até 3h antes)[cite: 7]';
        $auth->add($cancelarReserva);

        $consultarDetalhesHistorico = $auth->createPermission('consultarDetalhesHistorico');
        $consultarDetalhesHistorico->description = 'Consultar detalhes e histórico de reservas[cite: 7]';
        $auth->add($consultarDetalhesHistorico);

        $gerirFavoritos = $auth->createPermission('gerirFavoritos');
        $gerirFavoritos->description = 'Gerir favoritos (Sincronizado SQLite)[cite: 7]';
        $auth->add($gerirFavoritos);

        $avaliarSala = $auth->createPermission('avaliarSala');
        $avaliarSala->description = 'Avaliar sala (Comentário e 1-5 estrelas)[cite: 7]';
        $auth->add($avaliarSala);

        $checkinCheckoutMobile = $auth->createPermission('checkinCheckoutMobile');
        $checkinCheckoutMobile->description = 'Check-in e Check-out via Mobile (QR Code & Botão)[cite: 7]';
        $auth->add($checkinCheckoutMobile);


        // --- Módulo: Back-Office Op. (Operacional / Limpeza)[cite: 7] ---
        $acederBackoffice = $auth->createPermission('acederBackoffice');
        $acederBackoffice->description = 'Aceder ao Back-Office (Login Web)[cite: 7]';
        $auth->add($acederBackoffice);

        $verSalasEmLimpeza = $auth->createPermission('verSalasEmLimpeza');
        $verSalasEmLimpeza->description = 'Ver salas em limpeza (Mapa / Lista)[cite: 7]';
        $auth->add($verSalasEmLimpeza);

        $alterarParaDisponivel = $auth->createPermission('alterarParaDisponivel');
        $alterarParaDisponivel->description = 'Alterar para disponível (Botão final)[cite: 7]';
        $auth->add($alterarParaDisponivel);


        // --- Módulo: Back-Office Balcão (Receção)[cite: 7] ---
        $verMapaOcupacao = $auth->createPermission('verMapaOcupacao');
        $verMapaOcupacao->description = 'Ver mapa de ocupação (Diário em tempo real)[cite: 7]';
        $auth->add($verMapaOcupacao);

        $registarReservasManuais = $auth->createPermission('registarReservasManuais');
        $registarReservasManuais->description = 'Registar reservas manuais (Presenciais)[cite: 7]';
        $auth->add($registarReservasManuais);

        $efetuarCheckinManual = $auth->createPermission('efetuarCheckinManual');
        $efetuarCheckinManual->description = 'Efetuar check-in manual (Validação balcão)[cite: 7]';
        $auth->add($efetuarCheckinManual);


        // --- Módulo: Gestão Global (SysAdmin)[cite: 7] ---
        $gerirEdificiosEspacos = $auth->createPermission('gerirEdificiosEspacos');
        $gerirEdificiosEspacos->description = 'Gerir edifícios e espaços (CRUD Espaços)[cite: 7]';
        $auth->add($gerirEdificiosEspacos);

        $configurarPrecosCapacidades = $auth->createPermission('configurarPrecosCapacidades');
        $configurarPrecosCapacidades->description = 'Configurar preços e capacidades[cite: 7]';
        $auth->add($configurarPrecosCapacidades);

        $gerirUtilizadores = $auth->createPermission('gerirUtilizadores');
        $gerirUtilizadores->description = 'Gerir utilizadores (Contas e Roles)[cite: 7]';
        $auth->add($gerirUtilizadores);



        // =========================================================
        // 3. CRIAR OS 4 ROLES E ASSOCIAR AS PERMISSÕES (Seguindo a Tabela)[cite: 7]
        // =========================================================

        // Role 1: Cliente[cite: 7]
        $cliente = $auth->createRole('cliente');
        $auth->add($cliente);
        $auth->addChild($cliente, $pesquisarEspacos);
        $auth->addChild($cliente, $calcularPreco);
        $auth->addChild($cliente, $efetuarReservaPagamento);
        $auth->addChild($cliente, $cancelarReserva);
        $auth->addChild($cliente, $consultarDetalhesHistorico);
        $auth->addChild($cliente, $gerirFavoritos);
        $auth->addChild($cliente, $avaliarSala);
        $auth->addChild($cliente, $checkinCheckoutMobile);

        // Role 2: Limpeza (Equipa de Limpeza)[cite: 7]
        $limpeza = $auth->createRole('equipa_de_limpeza');
        $auth->add($limpeza);
        $auth->addChild($limpeza, $acederBackoffice);
        $auth->addChild($limpeza, $verSalasEmLimpeza);
        $auth->addChild($limpeza, $alterarParaDisponivel);

        // Role 3: Receção (Rececionista)[cite: 7]
        $rececao = $auth->createRole('rececionista');
        $auth->add($rececao);
        $auth->addChild($rececao, $acederBackoffice);
        $auth->addChild($rececao, $verSalasEmLimpeza);
        $auth->addChild($rececao, $verMapaOcupacao);
        $auth->addChild($rececao, $registarReservasManuais);
        $auth->addChild($rececao, $efetuarCheckinManual);

        // Role 4: SysAdmin (Administrador Global - Herda Limpeza e Receção + Gestão Global através de herança)[cite: 7]
        $sysAdmin = $auth->createRole('sys_admin');
        $auth->add($sysAdmin);
        $auth->addChild($sysAdmin, $rececao);
        $auth->addChild($sysAdmin, $limpeza);
        $auth->addChild($sysAdmin, $gerirEdificiosEspacos);
        $auth->addChild($sysAdmin, $configurarPrecosCapacidades);
        $auth->addChild($sysAdmin, $gerirUtilizadores);


        // =========================================================
        // 4. ATRIBUIÇÃO DE PERFIL INICIAL AO UTILIZADOR ID = 1
        // =========================================================
        $auth->assign($sysAdmin, 1);

        echo "RBAC do CoWook atualizado e configurado com sucesso segundo a matriz oficial!\n";
    }
}