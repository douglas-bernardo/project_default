<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7230d7ff72d3f5b3ad994d0601f5f4ea
{
    public static $files = array (
        '7dc4b6f9ee593fc2a3ea307d4199ec7f' => __DIR__ . '/../..' . '/init.php',
        'b42365d23a54a81cb1e09e48e0067689' => __DIR__ . '/../..' . '/lib/library/Support/Helpers.php',
        'c89a91e8362c2a538aa38c6e0dad83bf' => __DIR__ . '/../..' . '/services/boot/Config.php',
        'b678a33e61b122510090acfb060bc723' => __DIR__ . '/../..' . '/services/boot/Helpers.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Services\\' => 9,
        ),
        'L' => 
        array (
            'Library\\' => 8,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Services\\' => 
        array (
            0 => __DIR__ . '/../..' . '/services/source',
        ),
        'Library\\' => 
        array (
            0 => __DIR__ . '/../..' . '/lib/library',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'Cidade' => __DIR__ . '/../..' . '/app/model/Cidade.php',
        'Cliente' => __DIR__ . '/../..' . '/app/model/Cliente.php',
        'Company' => __DIR__ . '/../..' . '/app/model/Company.php',
        'Conta' => __DIR__ . '/../..' . '/app/model/Conta.php',
        'Contrato' => __DIR__ . '/../..' . '/app/model/Contrato.php',
        'Grupo' => __DIR__ . '/../..' . '/app/model/Grupo.php',
        'HelpersTest' => __DIR__ . '/../..' . '/app/control/tests/HelpersTest.php',
        'HomeControl' => __DIR__ . '/../..' . '/app/control/HomeControl.php',
        'ItemVenda' => __DIR__ . '/../..' . '/app/model/ItemVenda.php',
        'LoginForm' => __DIR__ . '/../..' . '/app/control/LoginForm.php',
        'ModelTest1' => __DIR__ . '/../..' . '/app/control/tests/ModelTest1.php',
        'Motivo' => __DIR__ . '/../..' . '/app/model/Motivo.php',
        'MotivoService' => __DIR__ . '/../..' . '/app/apiservices/MotivoService.php',
        'Negociacao' => __DIR__ . '/../..' . '/app/model/Negociacao.php',
        'NegociacaoForm' => __DIR__ . '/../..' . '/app/control/NegociacaoForm.php',
        'NegociacaoList' => __DIR__ . '/../..' . '/app/control/NegociacaoList.php',
        'Ocorrencia' => __DIR__ . '/../..' . '/app/model/Ocorrencia.php',
        'OcorrenciaService' => __DIR__ . '/../..' . '/app/apiservices/OcorrenciaService.php',
        'OcorrenciasList' => __DIR__ . '/../..' . '/app/control/OcorrenciasList.php',
        'Origem' => __DIR__ . '/../..' . '/app/model/Origem.php',
        'PermissionGroup' => __DIR__ . '/../..' . '/app/model/PermissionGroup.php',
        'Permissions' => __DIR__ . '/../..' . '/app/model/Permissions.php',
        'Pessoa' => __DIR__ . '/../..' . '/app/model/Pessoa.php',
        'PessoaGrupo' => __DIR__ . '/../..' . '/app/model/PessoaGrupo.php',
        'Produto' => __DIR__ . '/../..' . '/app/model/Produto.php',
        'Projeto' => __DIR__ . '/../..' . '/app/model/Projeto.php',
        'ProjetoTsService' => __DIR__ . '/../..' . '/app/apiservices/ProjetoTsService.php',
        'Retencao' => __DIR__ . '/../..' . '/app/model/Retencao.php',
        'Reversao' => __DIR__ . '/../..' . '/app/model/Reversao.php',
        'Situacao' => __DIR__ . '/../..' . '/app/model/Situacao.php',
        'TSLancamentos' => __DIR__ . '/../..' . '/app/model/TSLancamentos.php',
        'TestService' => __DIR__ . '/../..' . '/app/apiservices/TestService.php',
        'Tipo' => __DIR__ . '/../..' . '/app/model/Tipo.php',
        'TipoSolicitacao' => __DIR__ . '/../..' . '/app/model/TipoSolicitacao.php',
        'Unidade' => __DIR__ . '/../..' . '/app/model/Unidade.php',
        'Users' => __DIR__ . '/../..' . '/app/model/Users.php',
        'UsersForm' => __DIR__ . '/../..' . '/app/control/UsersForm.php',
        'UsersList' => __DIR__ . '/../..' . '/app/control/UsersList.php',
        'Venda' => __DIR__ . '/../..' . '/app/model/Venda.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7230d7ff72d3f5b3ad994d0601f5f4ea::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7230d7ff72d3f5b3ad994d0601f5f4ea::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7230d7ff72d3f5b3ad994d0601f5f4ea::$classMap;

        }, null, ClassLoader::class);
    }
}
