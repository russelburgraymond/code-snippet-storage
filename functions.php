<?php

function h($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function selected($a, $b): string
{
    return ((string)$a === (string)$b) ? 'selected' : '';
}

function snippet_languages(): array
{
    return [
        "",
        "PHP",
        "MySQL",
        "JavaScript",
        "HTML",
        "CSS",
        "JSON",
        "XML",
        "Python",
        "Shell",
        "PowerShell",
        "Java",
        "C#",
        "C++",
        "Other"
    ];
}
