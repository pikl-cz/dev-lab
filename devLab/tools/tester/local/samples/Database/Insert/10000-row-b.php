<?php

require '/../Connection/Open.php';

require '/../../GenerateHash/10000-b.php';

$sql = "INSERT INTO emails (email, date_created) VALUES ('john@example.com', NOW())";

include '../Connection/Close.php';