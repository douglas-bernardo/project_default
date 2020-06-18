<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7230d7ff72d3f5b3ad994d0601f5f4ea
{
    public static $prefixLengthsPsr4 = array (
        'L' => 
        array (
            'Livro\\' => 6,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Livro\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Lib/Livro',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/App',
        ),
    );

    public static $classMap = array (
        'Cidade' => __DIR__ . '/../..' . '/App/Model/Cidade.php',
        'Cliente' => __DIR__ . '/../..' . '/App/Model/Cliente.php',
        'Company' => __DIR__ . '/../..' . '/App/Model/Company.php',
        'Conta' => __DIR__ . '/../..' . '/App/Model/Conta.php',
        'Estado' => __DIR__ . '/../..' . '/App/Model/Estado.php',
        'Fabricante' => __DIR__ . '/../..' . '/App/Model/Fabricante.php',
        'Grupo' => __DIR__ . '/../..' . '/App/Model/Grupo.php',
        'HomeControl' => __DIR__ . '/../..' . '/App/Control/HomeControl.php',
        'ItemVenda' => __DIR__ . '/../..' . '/App/Model/ItemVenda.php',
        'LoginForm' => __DIR__ . '/../..' . '/App/Control/LoginForm.php',
        'PermissionGroup' => __DIR__ . '/../..' . '/App/Model/PermissionGroup.php',
        'Permissions' => __DIR__ . '/../..' . '/App/Model/Permissions.php',
        'Pessoa' => __DIR__ . '/../..' . '/App/Model/Pessoa.php',
        'PessoaGrupo' => __DIR__ . '/../..' . '/App/Model/PessoaGrupo.php',
        'Produto' => __DIR__ . '/../..' . '/App/Model/Produto.php',
        'Tipo' => __DIR__ . '/../..' . '/App/Model/Tipo.php',
        'Unidade' => __DIR__ . '/../..' . '/App/Model/Unidade.php',
        'Users' => __DIR__ . '/../..' . '/App/Model/Users.php',
        'UsersForm' => __DIR__ . '/../..' . '/App/Control/UsersForm.php',
        'UsersList' => __DIR__ . '/../..' . '/App/Control/UsersList.php',
        'Venda' => __DIR__ . '/../..' . '/App/Model/Venda.php',
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