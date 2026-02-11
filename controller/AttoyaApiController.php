<?php


#
# Attoya API Controller Class
#
class AttoyaApiController {

  #
  # API
  #
  // api_source = None
  // api_url    = None
  // api_key    = None




  #
  # Send Request
  #
  // def send_request(self, **kwargs):

  //   kwargs = default_empty_dict(kwargs, {
  //     'url'     : self.api_url,
  //     'headers' : {},
  //     'data'    : {},
  //     'is_json' : False,
  //   })

  //   if is_empty(kwargs.get('url')) == True:
  //     return None


  //   import requests

  //   # Send request
  //   response = requests.get(
  //     kwargs.get('url'),
  //     headers = kwargs.get('headers'),
  //     data    = kwargs.get('data')
  //   )

  //   # Return none if no response or not ok response
  //   if is_empty(response) == True or response.status_code != http_status.HTTP_200_OK:
  //     Console.warn(self.__class__.__name__ + '.send_request() invalid response', **{
  //       'request'  : kwargs,
  //       'response' : response,
  //     })
  //     return None

  //   if kwargs.get('is_json') == True:
  //     return response.json()

  //   return response




}


