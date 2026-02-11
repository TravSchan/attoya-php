<?php


#
# Attoya System Controller Class
#
class AttoyaSystemController {

  #
  # URL Pattern
  #
  private static $url_pattern;



  #
  # Init Application
  #
  public static function init() {

    # Load Attoya PHP
    include_once VENDOR_ROOT . '/attoya/php8_compatibility.php' ;
    include_once VENDOR_ROOT . '/attoya/logic.php' ;
    include_once VENDOR_ROOT . '/attoya/Console.php' ;

    if(DEBUG_FRAMEWORK == true) {
      Console::log('AttoyaSystemController->init()');
    }


    # Exception
    include_once SYSTEM_ROOT . '/lib/base/handler/ExceptionHandler.php' ;
    set_exception_handler(['ExceptionHandler', 'init']);


    self::getView();

  }



  #
  # Set URL Pattern
  #
  public static function setUrlPattern($url_pattern = []) {

    self::$url_pattern = $url_pattern;

  }



  #
  # Get URL Pattern
  #
  public static function getUrlPattern() {

    return self::$url_pattern;

  }



  #
  # Get View
  #
  public static function getView() {

    if(DEBUG_FRAMEWORK == true) {
      Console::log('AttoyaSystemController->getView()');
    }

    // Check request to find URI
    $request_uri = $_SERVER['REQUEST_URI'];

    // Remove get parameters
    if(strpos($request_uri, '?') > 0) {
      $request_uri = substr($request_uri, 0, strpos($request_uri, '?'));
    }

    if(DEBUG_FRAMEWORK == true) {
      Console::log('AttoyaSystemController->getView() $request_uri ' . strval($request_uri));
    }

    // Remove trailing slashes
    if(substr($request_uri, -1) == '/') {
      $request_uri = substr($request_uri, 0, -1);
    }

    // Default to root route
    if($request_uri == '') {
      $request_uri = '/';
    }

    // Convert to array
    $request_uri_parts = explode('/', $request_uri);

    $found       = false;
    $view_kwargs = [
      'user_id'  => null,
      'model_id' => null,
    ];

    // Referer is not always set. Default to empty
    if(isset($_SERVER['HTTP_REFERER']) == false) {
      $_SERVER['HTTP_REFERER'] = '';
    }

    # Request
    $view_request = [
      'referer'   => $_SERVER['HTTP_REFERER'],
      'remote_ip' => $_SERVER['REMOTE_ADDR'],
      'uri'       => $_SERVER['REQUEST_URI'],
      'method'    => strtolower($_SERVER['REQUEST_METHOD']),
      'route'     => null,
    ];

    // Find Matching Route
    foreach(self::getUrlPattern() as $app_name => $view_list) {
      foreach($view_list as $view_url => $view_options) {

        // Defaults
        if(empty($view_options)) { $view_options = []; }
        if(array_key_exists('route',    $view_options) == false) { $view_options['route']    = null; }
        if(array_key_exists('path',     $view_options) == false) { $view_options['path']    = null; }
        if(array_key_exists('class',    $view_options) == false) { $view_options['class']    = null; }
        if(array_key_exists('action',   $view_options) == false) { $view_options['action']   = null; }
        if(array_key_exists('redirect', $view_options) == false) { $view_options['redirect'] = null; }

        // @issue default_empty_array() not working on production
        // $view_options = default_empty_array($view_options, [
        //   'route'    => null,
        //   'path'     => null,
        //   'class'    => null,
        //   'action'   => null,
        //   'redirect' => null,
        // ]);

        if(DEBUG_FRAMEWORK == true) {
          Console::log('AttoyaSystemController->getView() $request_uri loop ' . print_r([
            '$request_uri'  => $request_uri,
            '$view_url'     => $view_url,
            // '$view_options' => $view_options,
          ], true));
        }


        // Route Type Checks
        if ($request_uri == $view_url) { // Route Simple

          $view_request['route'] = $view_options['route'];

          $found = true; // Exact match

        }
        else { // Route Dynamic

          // @wip pass for now, dynamic routing not working and should be disable for production
          continue;

          // $view_parts = explode('/', substr($view_url, 1));

          // Get Route Dynamic Values
          $view_dynamic_values = null;
          if (strpos($view_url, '{') > 0) {
            preg_match_all('/{\K[^}]*(?=})/m', $view_url, $values);
            $view_dynamic_values = $values; //[0];
          }

          if($view_dynamic_values != null) {

            if(DEBUG_FRAMEWORK == true) {
              Console::log('AttoyaSystemController->getView() Route Dynamic Values ' . print_r([
                '$view_url {'          => print_r(strpos($view_url, '{'), true),
                '$view_dynamic_values' => $view_dynamic_values,
                '$request_uri_parts'   => $request_uri_parts,
              ], true));
            }

            if(count($view_dynamic_values) == (count($request_uri_parts) - 2)) {

              // Dynamic route with matching dynamic value counts
              // Map dynamic routes to an array to be supplied to Action class
              foreach($view_dynamic_values as $key => $view_dynamic_value) {

                // self::handleRedirect($view_options); // Route Redirect

                // if(DEBUG_FRAMEWORK == true) {
                //   Console::log('AttoyaSystemController->getView() view_dynamic_values ' . print_r([
                //     '$key'                => print_r($key, true),
                //     '$view_dynamic_value' => $view_dynamic_value,
                //   ], true));
                // }

                $view_kwargs[$view_dynamic_value] = $request_uri_parts[$key + 2];

              }

              $view_request['route'] = $view_options['route'];

              $found = true;

            }
          }

        }


        // Found Route
        if($found == true) {

          self::handleRedirect($view_options); // Route Redirect

          $view_path   = $view_options['path'];
          $view_class  = $view_options['class'];
          $view_action = $view_options['action'];

          // Make sure has trailing View
          if(str_ends_with($view_options['class'], 'View') == false) {
            $view_class .= 'View';
          }

          if(DEBUG_FRAMEWORK == true) {
            Console::log('AttoyaSystemController->getView() $app_name '   . strval($app_name));
            Console::log('AttoyaSystemController->getView() $view_path '  . strval($view_path));
            Console::log('AttoyaSystemController->getView() $view_class ' . strval($view_class));
            Console::log('AttoyaSystemController->getView() $view_action '. strval($view_action));
            Console::log('AttoyaSystemController->getView() $view_kwargs '. print_r($view_kwargs, true));
          }

          break 2; // Exit view list loop

        }

      }
    }


    if(empty($view_path) == false) {

      // Add trailing slash
      $view_path = rtrim($view_path, '/') . '/';

      // Check if Path Exists, if not default to none
      $include_path = SYSTEM_ROOT . '/app/' . $app_name . '/view/' . $view_path;
      if(is_dir($include_path) == false) {
        if(DEBUG_FRAMEWORK == true) {
          Console::log('AttoyaSystemController->getView() unable to load view path ' . strval($view_path));
        }
        $view_path = '';
      }

    }


    // Check if Class Exists, if not default to 404
    if(empty($view_class) == true) {
      $app_name    = 'core';
      $view_path   = '';
      $view_class  = 'CoreErrorView';
      $view_action = 'handle_invalid_view';
    }


    $include_class = SYSTEM_ROOT . '/app/' . $app_name . '/view/' . $view_path . $view_class . '.php';
    if(is_file($include_class) == true) {
      include_once $include_class;
    }
    else {

      if(DEBUG_FRAMEWORK == true) {
        Console::log('AttoyaSystemController->getView() unable to load view ' . strval($include_class));
      }

      $app_name      = 'core';
      $view_path     = '';
      $view_class    = 'CoreErrorView';
      $view_action   = 'handle_invalid_view';
      $include_class = SYSTEM_ROOT . '/app/' . $app_name . '/view/' . $view_path . $view_class . '.php';

      include_once $include_class;

    }


    // Create Action Class Instance
    $action_class = new $view_class($view_kwargs, $view_request);


    // Run action after init
    // @future Verify this is needed. Not sure off the top of my head as the AttoyaView->dispatch() does most of this.
    if(is_empty($view_action) == false) {

      // Check if Function Exists, if not default to 404
      if(method_exists($view_class, $view_action) == false) {

        if(DEBUG_FRAMEWORK == true) {
          Console::log('AttoyaSystemController->getView() view_action is not a method on view glass ' . strval($view_action));
        }

        $view_action = 'handle_invalid_view';

      }

      // Call Action Function
      $action_class->$view_action();

    }


  }



  #
  # Handle Redirect
  #
  public static function handleRedirect($view_options = []) {

    if (is_set($view_options['redirect']) == true) { // Route Redirect

      if(DEBUG_FRAMEWORK == true) {
        Console::log('AttoyaSystemController->getView() Route Redirect Values ' . print_r($view_options, true));
      }

      header('Location: ' . $view_options['redirect']);
      die();

    }

  }




}


