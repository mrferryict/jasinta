<?php

function f_template(): string
{
   return base_url('templates/AdminLTE/');
}

function f_login(): string
{
   return base_url('templates/Login/');
}

function f_images($path = ''): string
{
   return base_url('images/' . $path);
}
