<?php
// Cầu nối để Vercel nhận diện PHP
// Chuyển tất cả requests về index.php gốc

chdir('..');
require __DIR__ . '/../index.php';
