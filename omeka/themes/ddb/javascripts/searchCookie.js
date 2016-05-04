/*
 * Copyright (C) 2014 FIZ Karlsruhe
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/* Search namespace  */
de.ddb.next.search = de.ddb.next.search || {};

de.ddb.next.search.setSearchCookieParameter = function(arrayParamVal) {
  var searchParameters = de.ddb.next.search.readCookie("searchParameters" + jsContextPath);
  if (searchParameters != null && searchParameters.length > 0) {
    searchParameters = searchParameters.substring(1, searchParameters.length - 1);
    searchParameters = searchParameters.replace(/\\"/g, '"');
    var json = $.parseJSON(searchParameters);
    $.each(arrayParamVal, function(key, value) {
      if (value[1].constructor === Array) {
        for ( var i = 0; i < value[1].length; i++) {
          if (value[1][i].constructor === String) {
            value[1][i] = encodeURIComponent(value[1][i]).replace(/%20/g, '\+');
          }
        }
      } else if (value[1].constructor === String) {
        value[1] = encodeURIComponent(value[1]).replace(/%20/g, '\+');
      }
      json[value[0]] = value[1];
    });
    document.cookie = "searchParameters" + jsContextPath + "=\""
        + JSON.stringify(json).replace(/"/g, '\\"') + "\"";
  }
};

de.ddb.next.search.removeSearchCookieParameter = function(paramName) {
  var searchParameters = de.ddb.next.search.readCookie("searchParameters" + jsContextPath);
  if (searchParameters != null && searchParameters.length > 0) {
    searchParameters = searchParameters.substring(1, searchParameters.length - 1);
    searchParameters = searchParameters.replace(/\\"/g, '"');
    var json = $.parseJSON(searchParameters);
    //deletes the attribute from the JSON
    delete json[paramName];
    document.cookie = "searchParameters" + jsContextPath + "=\""
        + JSON.stringify(json).replace(/"/g, '\\"') + "\"";
  }
};

de.ddb.next.search.readCookie = function(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for ( var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) === ' ') {
      c = c.substring(1, c.length);
    }
    if (c.indexOf(nameEQ) === 0) {
      return c.substring(nameEQ.length, c.length);
    }
  }
  return null;
};