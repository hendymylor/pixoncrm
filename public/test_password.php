<?php
$plain = 'Akuang742748';
$hash  = '$2y$12$AWQzhwKvHFoPgksPAS/9TuIe3.bMlS4h7JlMnbVhxksnop2yHLlHC';

var_dump(password_verify($plain, $hash));