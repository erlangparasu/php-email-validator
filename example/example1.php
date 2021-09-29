<?php

require __DIR__ . '/../vendor/autoload.php';

var_dump(_is_email_valid('dummyuser1@gmail.com'));

var_dump(_is_email_valid('dummyuser1@example.com'));

var_dump(_is_email_valid('dummyuser1@localhost.com'));

var_dump(_is_email_valid('dummyuser1@local'));

var_dump(_is_email_valid('dummyuser1@localhost'));

var_dump(_is_email_valid('dummyuser1@yahoo.co.id'));
