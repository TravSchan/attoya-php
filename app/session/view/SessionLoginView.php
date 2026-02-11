<?php


# Lib Include
include_once SYSTEM_ROOT . '/lib/base/view/PublicView.php';




#
# Core Login View Class
#
class CoreLoginView extends PublicView {




  #
  # Init
  #
  public function init() {

    parent::init();

    $this->template_name = 'session/template/login.tpl';

  }




  #
  # Render Data
  #
  function render_data($response = []) {

    $response = parent::render_data($response);

    $response['title'] = [
      'page'    => 'Login',
      'section' => '', # Don't show next to logo, just on head title
    ];

    return $response;

  }




}


