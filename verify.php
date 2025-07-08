<?php
$pass = '9qLp@2WsFx!7g#Tz';
$hash = '$2y$10$EfwO2J6I5yHm9acvNpKZ8ekOpaOJ0hwHX1rHW2jBvM1vEpFQ0DzQ6';

if (password_verify($pass, $hash)) {
    echo "✅ Пароль вірний";
} else {
  echo password_hash('9qLp@2WsFx!7g#Tz', PASSWORD_DEFAULT);
  
}
