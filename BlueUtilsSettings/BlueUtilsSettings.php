<?php

namespace BluePost;

class BlueUtilsSettings {

  public $SENDGRID_API_KEY = "";

  public $PATH_TO_VENDOR = __DIR__ . "/../../vendor/";

  public $PROJECT_ROOT_DIR = __DIR__ . "/../../";

  public $DB_CONFIG = Array (
      "HOST"		=>	"<DB_HOST>",
      "USERNAME"	=>	"<USERNAME>",
      "PASSWORD"	=>	"<PASSWORD>",
      "NAME"		=>	"<DB_NAME>"
    );

}
