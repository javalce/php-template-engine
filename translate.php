#!/usr/bin/env php
<?php

function translateInit($lang)
{
    $potFile = "messages.pot";
    $translateDir = sprintf("translations/%s/LC_MESSAGES", $lang);
    $translateFile = $translateDir . DIRECTORY_SEPARATOR . "messages.po";
    $extractCommand = sprintf("xgettext --add-comments --sort-output -o %s $(find templates -name '*.php')", $potFile);
    $initCommand = sprintf("msginit --no-translator --input %s --output %s", $potFile, $translateFile);
    shell_exec($extractCommand);
    mkdir(__DIR__ . DIRECTORY_SEPARATOR . $translateDir, 0744, true);
    shell_exec($initCommand);
    unlink($potFile);
}

function translateUpdate()
{
    $potFile = "messages.pot";
    $files = shell_exec("find translations -name '*.po'");
    $files = array_filter(preg_split("/\s+/", $files));
    $extractCommand = sprintf("xgettext --add-comments --sort-output -o %s $(find templates -name '*.php')", $potFile);
    shell_exec($extractCommand);
    foreach ($files as $file) {
        $updateCommand = sprintf("msgmerge --update %s %s", $file, $potFile);
        shell_exec($updateCommand);
    }
    unlink($potFile);
}

function translateCompile()
{
    $files = shell_exec("find translations -name '*.po'");
    $files = array_filter(preg_split("/\s+/", $files));
    foreach ($files as $file) {
        $compileFile = str_replace('.po', '.mo', $file);
        $compileCommand = sprintf("msgfmt %s --output-file=%s", $file, $compileFile);
        shell_exec($compileCommand);
    }
}

$command = $argv[1] ?? null;
$lang = $argv[2] ?? null;

switch ($command) {
    case "init":
        translateInit($lang);
        break;
    case "update":
        translateUpdate();
        break;
    case "compile":
        translateCompile();
        break;
    default:
        echo sprintf("Command %s not found.\n", $command);
        break;
}