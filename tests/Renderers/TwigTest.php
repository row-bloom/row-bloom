<?php

use Twig\Loader\ArrayLoader;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

it('renders array data and string template', function () {
    $templates = [
        'template_name' => '<h1>{{ title }}</h1>',
    ];

    $data = [
        'title' => 'My Page Title',
        'content' => 'Welcome to my website!',
    ];

    $loader = new ArrayLoader($templates);
    $twig = new Environment($loader);

    $template = $twig->load('template_name');
    $html = $template->render($data);

    expect($html)->toBe('<h1>My Page Title</h1>');
});

it('renders array data and file template', function () {
    $data = [
        'title' => 'My Page Title',
        'content' => 'Welcome to my website!',
    ];

    $loader = new FilesystemLoader(__DIR__ . '/stubs');
    $twig = new Environment($loader);

    $template = $twig->load('template_name.twig');
    $html = $template->render($data);

    expect($html)->toBe('<h1>My Page Title</h1>');
});
