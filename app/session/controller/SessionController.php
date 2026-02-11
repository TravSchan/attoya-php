<?php


# Lib Include
include_once SYSTEM_ROOT . '/lib/base/controller/BaseController.php';

# Lib Import
# from lib.contrib.auth.model import User
// from apps.system.session.models.User import User
// from lib.controller.Controller import Controller
// from lib.contrib.auth import login as django_login
// from lib.contrib.auth import logout as django_logout






class SessionController extends BaseController {




  #
  # Authenticate User
  # @param object request
  # @param string email
  #
  // def auth_user(self, request, email = None):

  //   # If the email is emtpy, authentication Failed
  //   if email == None:
  //     return False

  //   user = self.fetch_user(email)

  //   if user == None:
  //     return False

  //   django_login(request, user)

  //   return True




  // #
  // # Deauthenticate User
  // # @param object request
  // # @param string email
  // #
  // def deauth_user(self, request):

  //   django_logout(request)




  // #
  // # Fetch User
  // # @param string email
  // #
  // def fetch_user(self, email):

  //   try:
  //     user = User.objects.filter(email = email).first()

  //   except User.DoesNotExist:
  //     return None

  //   # Check if user exist
  //   if user == None:
  //     return None

  //   # Double check user is active
  //   if user.is_active != True:
  //     return None

  //   return user

}



