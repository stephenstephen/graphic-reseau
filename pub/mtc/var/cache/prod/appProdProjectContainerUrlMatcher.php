<?php

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class appProdProjectContainerUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function match($rawPathinfo)
    {
        $allow = [];
        $pathinfo = rawurldecode($rawPathinfo);
        $trimmedPathinfo = rtrim($pathinfo, '/');
        $context = $this->context;
        $request = $this->request ?: $this->createRequest($pathinfo);
        $requestMethod = $canonicalMethod = $context->getMethod();

        if ('HEAD' === $requestMethod) {
            $canonicalMethod = 'GET';
        }

        // mautic_js
        if ('/mtc.js' === $pathinfo) {
            $ret = array (  '_controller' => 'Mautic\\CoreBundle\\Controller\\JsController::indexAction',  '_route' => 'mautic_js',);
            $requiredSchemes = array (  'https' => 0,);
            if (!isset($requiredSchemes[$context->getScheme()])) {
                if ('GET' !== $canonicalMethod) {
                    goto not_mautic_js;
                }

                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_js', key($requiredSchemes)));
            }

            return $ret;
        }
        not_mautic_js:

        // mautic_base_index
        if ('/' === $pathinfo) {
            $ret = array (  '_controller' => 'Mautic\\CoreBundle\\Controller\\DefaultController::indexAction',  '_route' => 'mautic_base_index',);
            $requiredSchemes = array (  'https' => 0,);
            if (!isset($requiredSchemes[$context->getScheme()])) {
                if ('GET' !== $canonicalMethod) {
                    goto not_mautic_base_index;
                }

                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_base_index', key($requiredSchemes)));
            }

            return $ret;
        }
        not_mautic_base_index:

        if (0 === strpos($pathinfo, '/s')) {
            // mautic_secure_root
            if ('/s' === $pathinfo) {
                $ret = array (  '_controller' => 'Mautic\\CoreBundle\\Controller\\DefaultController::redirectSecureRootAction',  '_route' => 'mautic_secure_root',);
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_secure_root;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_secure_root', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_secure_root:

            // mautic_secure_root_slash
            if ('/s/' === $pathinfo) {
                $ret = array (  '_controller' => 'Mautic\\CoreBundle\\Controller\\DefaultController::redirectSecureRootAction',  '_route' => 'mautic_secure_root_slash',);
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_secure_root_slash;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_secure_root_slash', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_secure_root_slash:

        }

        // mautic_remove_trailing_slash
        if (preg_match('#^/(?P<url>.*/)$#sD', $pathinfo, $matches)) {
            $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_remove_trailing_slash']), array (  '_controller' => 'Mautic\\CoreBundle\\Controller\\CommonController::removeTrailingSlashAction',));
            $requiredSchemes = array (  'https' => 0,);
            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
            if (!in_array($canonicalMethod, ['GET'])) {
                if ($hasRequiredScheme) {
                    $allow = array_merge($allow, ['GET']);
                }
                goto not_mautic_remove_trailing_slash;
            }
            if (!$hasRequiredScheme) {
                if ('GET' !== $canonicalMethod) {
                    goto not_mautic_remove_trailing_slash;
                }

                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_remove_trailing_slash', key($requiredSchemes)));
            }

            return $ret;
        }
        not_mautic_remove_trailing_slash:

        if (0 === strpos($pathinfo, '/oauth/v1')) {
            // bazinga_oauth_server_requesttoken
            if ('/oauth/v1/request_token' === $pathinfo) {
                $ret = array (  '_controller' => 'bazinga.oauth.controller.server:requestTokenAction',  '_route' => 'bazinga_oauth_server_requesttoken',);
                $requiredSchemes = array (  'https' => 0,);
                $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                if (!in_array($canonicalMethod, ['GET', 'POST'])) {
                    if ($hasRequiredScheme) {
                        $allow = array_merge($allow, ['GET', 'POST']);
                    }
                    goto not_bazinga_oauth_server_requesttoken;
                }
                if (!$hasRequiredScheme) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_bazinga_oauth_server_requesttoken;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'bazinga_oauth_server_requesttoken', key($requiredSchemes)));
                }

                return $ret;
            }
            not_bazinga_oauth_server_requesttoken:

            if (0 === strpos($pathinfo, '/oauth/v1/authorize')) {
                // bazinga_oauth_login_allow
                if ('/oauth/v1/authorize' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\ApiBundle\\Controller\\oAuth1\\AuthorizeController::allowAction',  '_route' => 'bazinga_oauth_login_allow',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($canonicalMethod, ['GET'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['GET']);
                        }
                        goto not_bazinga_oauth_login_allow;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_bazinga_oauth_login_allow;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'bazinga_oauth_login_allow', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_bazinga_oauth_login_allow:

                // bazinga_oauth_server_authorize
                if ('/oauth/v1/authorize' === $pathinfo) {
                    $ret = array (  '_controller' => 'bazinga.oauth.controller.server:authorizeAction',  '_route' => 'bazinga_oauth_server_authorize',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['POST'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['POST']);
                        }
                        goto not_bazinga_oauth_server_authorize;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_bazinga_oauth_server_authorize;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'bazinga_oauth_server_authorize', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_bazinga_oauth_server_authorize:

                if (0 === strpos($pathinfo, '/oauth/v1/authorize_login')) {
                    // mautic_oauth1_server_auth_login
                    if ('/oauth/v1/authorize_login' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\ApiBundle\\Controller\\oAuth1\\SecurityController::loginAction',  '_route' => 'mautic_oauth1_server_auth_login',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET', 'POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET', 'POST']);
                            }
                            goto not_mautic_oauth1_server_auth_login;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_oauth1_server_auth_login;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_oauth1_server_auth_login', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_oauth1_server_auth_login:

                    // mautic_oauth1_server_auth_login_check
                    if ('/oauth/v1/authorize_login_check' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\ApiBundle\\Controller\\oAuth1\\SecurityController::loginCheckAction',  '_route' => 'mautic_oauth1_server_auth_login_check',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET', 'POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET', 'POST']);
                            }
                            goto not_mautic_oauth1_server_auth_login_check;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_oauth1_server_auth_login_check;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_oauth1_server_auth_login_check', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_oauth1_server_auth_login_check:

                }

            }

            // bazinga_oauth_server_accesstoken
            if ('/oauth/v1/access_token' === $pathinfo) {
                $ret = array (  '_controller' => 'bazinga.oauth.controller.server:accessTokenAction',  '_route' => 'bazinga_oauth_server_accesstoken',);
                $requiredSchemes = array (  'https' => 0,);
                $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                if (!in_array($canonicalMethod, ['GET', 'POST'])) {
                    if ($hasRequiredScheme) {
                        $allow = array_merge($allow, ['GET', 'POST']);
                    }
                    goto not_bazinga_oauth_server_accesstoken;
                }
                if (!$hasRequiredScheme) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_bazinga_oauth_server_accesstoken;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'bazinga_oauth_server_accesstoken', key($requiredSchemes)));
                }

                return $ret;
            }
            not_bazinga_oauth_server_accesstoken:

        }

        elseif (0 === strpos($pathinfo, '/oauth/v2')) {
            // fos_oauth_server_token
            if ('/oauth/v2/token' === $pathinfo) {
                $ret = array (  '_controller' => 'fos_oauth_server.controller.token:tokenAction',  '_route' => 'fos_oauth_server_token',);
                $requiredSchemes = array (  'https' => 0,);
                $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                if (!in_array($canonicalMethod, ['GET', 'POST'])) {
                    if ($hasRequiredScheme) {
                        $allow = array_merge($allow, ['GET', 'POST']);
                    }
                    goto not_fos_oauth_server_token;
                }
                if (!$hasRequiredScheme) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_fos_oauth_server_token;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'fos_oauth_server_token', key($requiredSchemes)));
                }

                return $ret;
            }
            not_fos_oauth_server_token:

            // fos_oauth_server_authorize
            if ('/oauth/v2/authorize' === $pathinfo) {
                $ret = array (  '_controller' => 'Mautic\\ApiBundle\\Controller\\oAuth2\\AuthorizeController::authorizeAction',  '_route' => 'fos_oauth_server_authorize',);
                $requiredSchemes = array (  'https' => 0,);
                $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                if (!in_array($canonicalMethod, ['GET', 'POST'])) {
                    if ($hasRequiredScheme) {
                        $allow = array_merge($allow, ['GET', 'POST']);
                    }
                    goto not_fos_oauth_server_authorize;
                }
                if (!$hasRequiredScheme) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_fos_oauth_server_authorize;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'fos_oauth_server_authorize', key($requiredSchemes)));
                }

                return $ret;
            }
            not_fos_oauth_server_authorize:

            if (0 === strpos($pathinfo, '/oauth/v2/authorize_login')) {
                // mautic_oauth2_server_auth_login
                if ('/oauth/v2/authorize_login' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\ApiBundle\\Controller\\oAuth2\\SecurityController::loginAction',  '_route' => 'mautic_oauth2_server_auth_login',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($canonicalMethod, ['GET', 'POST'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['GET', 'POST']);
                        }
                        goto not_mautic_oauth2_server_auth_login;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_oauth2_server_auth_login;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_oauth2_server_auth_login', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_oauth2_server_auth_login:

                // mautic_oauth2_server_auth_login_check
                if ('/oauth/v2/authorize_login_check' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\ApiBundle\\Controller\\oAuth2\\SecurityController::loginCheckAction',  '_route' => 'mautic_oauth2_server_auth_login_check',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($canonicalMethod, ['GET', 'POST'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['GET', 'POST']);
                        }
                        goto not_mautic_oauth2_server_auth_login_check;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_oauth2_server_auth_login_check;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_oauth2_server_auth_login_check', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_oauth2_server_auth_login_check:

            }

        }

        // mautic_asset_download
        if (0 === strpos($pathinfo, '/asset') && preg_match('#^/asset(?:/(?P<slug>[^/]++))?$#sD', $pathinfo, $matches)) {
            $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_asset_download']), array (  'slug' => '',  '_controller' => 'Mautic\\AssetBundle\\Controller\\PublicController::downloadAction',));
            $requiredSchemes = array (  'https' => 0,);
            if (!isset($requiredSchemes[$context->getScheme()])) {
                if ('GET' !== $canonicalMethod) {
                    goto not_mautic_asset_download;
                }

                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_asset_download', key($requiredSchemes)));
            }

            return $ret;
        }
        not_mautic_asset_download:

        if (0 === strpos($pathinfo, '/api')) {
            if (0 === strpos($pathinfo, '/api/f')) {
                if (0 === strpos($pathinfo, '/api/files')) {
                    // mautic_core_api_file_list
                    if (preg_match('#^/api/files/(?P<dir>[^/]++)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_core_api_file_list']), array (  '_controller' => 'Mautic\\CoreBundle\\Controller\\Api\\FileApiController::listAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_core_api_file_list;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_core_api_file_list;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_core_api_file_list', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_core_api_file_list:

                    // mautic_core_api_file_create
                    if (preg_match('#^/api/files/(?P<dir>[^/]++)/new$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_core_api_file_create']), array (  '_controller' => 'Mautic\\CoreBundle\\Controller\\Api\\FileApiController::createAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_core_api_file_create;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_core_api_file_create;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_core_api_file_create', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_core_api_file_create:

                    // mautic_core_api_file_delete
                    if (preg_match('#^/api/files/(?P<dir>[^/]++)/(?P<file>[^/]++)/delete$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_core_api_file_delete']), array (  '_controller' => 'Mautic\\CoreBundle\\Controller\\Api\\FileApiController::deleteAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_core_api_file_delete;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_core_api_file_delete;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_core_api_file_delete', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_core_api_file_delete:

                }

                elseif (0 === strpos($pathinfo, '/api/fields')) {
                    // mautic_api_fields_getall
                    if (preg_match('#^/api/fields/(?P<object>[^/]++)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_fields_getall']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\FieldApiController::getEntitiesAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_fields_getall;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_fields_getall;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_fields_getall', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_fields_getall:

                    // mautic_api_fields_getone
                    if (preg_match('#^/api/fields/(?P<object>[^/]++)/(?P<id>\\d+)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_fields_getone']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\FieldApiController::getEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_fields_getone;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_fields_getone;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_fields_getone', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_fields_getone:

                    // mautic_api_fields_new
                    if (preg_match('#^/api/fields/(?P<object>[^/]++)/new$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_fields_new']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\FieldApiController::newEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_fields_new;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_fields_new;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_fields_new', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_fields_new:

                    // mautic_api_fields_newbatch
                    if (preg_match('#^/api/fields/(?P<object>[^/]++)/batch/new$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_fields_newbatch']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\FieldApiController::newEntitiesAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_fields_newbatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_fields_newbatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_fields_newbatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_fields_newbatch:

                    // mautic_api_fields_editbatchput
                    if (preg_match('#^/api/fields/(?P<object>[^/]++)/batch/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_fields_editbatchput']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\FieldApiController::editEntitiesAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PUT'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PUT']);
                            }
                            goto not_mautic_api_fields_editbatchput;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_fields_editbatchput;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_fields_editbatchput', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_fields_editbatchput:

                    // mautic_api_fields_editbatchpatch
                    if (preg_match('#^/api/fields/(?P<object>[^/]++)/batch/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_fields_editbatchpatch']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\FieldApiController::editEntitiesAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PATCH'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PATCH']);
                            }
                            goto not_mautic_api_fields_editbatchpatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_fields_editbatchpatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_fields_editbatchpatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_fields_editbatchpatch:

                    // mautic_api_fields_editput
                    if (preg_match('#^/api/fields/(?P<object>[^/]++)/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_fields_editput']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\FieldApiController::editEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PUT'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PUT']);
                            }
                            goto not_mautic_api_fields_editput;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_fields_editput;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_fields_editput', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_fields_editput:

                    // mautic_api_fields_editpatch
                    if (preg_match('#^/api/fields/(?P<object>[^/]++)/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_fields_editpatch']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\FieldApiController::editEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PATCH'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PATCH']);
                            }
                            goto not_mautic_api_fields_editpatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_fields_editpatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_fields_editpatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_fields_editpatch:

                    // mautic_api_fields_deletebatch
                    if (preg_match('#^/api/fields/(?P<object>[^/]++)/batch/delete$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_fields_deletebatch']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\FieldApiController::deleteEntitiesAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_fields_deletebatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_fields_deletebatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_fields_deletebatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_fields_deletebatch:

                    // mautic_api_fields_delete
                    if (preg_match('#^/api/fields/(?P<object>[^/]++)/(?P<id>\\d+)/delete$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_fields_delete']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\FieldApiController::deleteEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_fields_delete;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_fields_delete;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_fields_delete', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_fields_delete:

                }

                elseif (0 === strpos($pathinfo, '/api/forms')) {
                    // mautic_api_forms_getall
                    if ('/api/forms' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\FormBundle\\Controller\\Api\\FormApiController::getEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_forms_getall',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_forms_getall;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_forms_getall;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_forms_getall', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_forms_getall:

                    // mautic_api_forms_getone
                    if (preg_match('#^/api/forms/(?P<id>\\d+)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_forms_getone']), array (  '_controller' => 'Mautic\\FormBundle\\Controller\\Api\\FormApiController::getEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_forms_getone;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_forms_getone;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_forms_getone', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_forms_getone:

                    // mautic_api_forms_new
                    if ('/api/forms/new' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\FormBundle\\Controller\\Api\\FormApiController::newEntityAction',  '_format' => 'json',  '_route' => 'mautic_api_forms_new',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_forms_new;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_forms_new;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_forms_new', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_forms_new:

                    // mautic_api_forms_newbatch
                    if ('/api/forms/batch/new' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\FormBundle\\Controller\\Api\\FormApiController::newEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_forms_newbatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_forms_newbatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_forms_newbatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_forms_newbatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_forms_newbatch:

                    if (0 === strpos($pathinfo, '/api/forms/batch/edit')) {
                        // mautic_api_forms_editbatchput
                        if ('/api/forms/batch/edit' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\FormBundle\\Controller\\Api\\FormApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_forms_editbatchput',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($requestMethod, ['PUT'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['PUT']);
                                }
                                goto not_mautic_api_forms_editbatchput;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_forms_editbatchput;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_forms_editbatchput', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_forms_editbatchput:

                        // mautic_api_forms_editbatchpatch
                        if ('/api/forms/batch/edit' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\FormBundle\\Controller\\Api\\FormApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_forms_editbatchpatch',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($requestMethod, ['PATCH'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['PATCH']);
                                }
                                goto not_mautic_api_forms_editbatchpatch;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_forms_editbatchpatch;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_forms_editbatchpatch', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_forms_editbatchpatch:

                    }

                    // mautic_api_forms_editput
                    if (preg_match('#^/api/forms/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_forms_editput']), array (  '_controller' => 'Mautic\\FormBundle\\Controller\\Api\\FormApiController::editEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PUT'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PUT']);
                            }
                            goto not_mautic_api_forms_editput;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_forms_editput;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_forms_editput', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_forms_editput:

                    // mautic_api_forms_editpatch
                    if (preg_match('#^/api/forms/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_forms_editpatch']), array (  '_controller' => 'Mautic\\FormBundle\\Controller\\Api\\FormApiController::editEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PATCH'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PATCH']);
                            }
                            goto not_mautic_api_forms_editpatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_forms_editpatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_forms_editpatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_forms_editpatch:

                    // mautic_api_forms_deletebatch
                    if ('/api/forms/batch/delete' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\FormBundle\\Controller\\Api\\FormApiController::deleteEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_forms_deletebatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_forms_deletebatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_forms_deletebatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_forms_deletebatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_forms_deletebatch:

                    // mautic_api_forms_delete
                    if (preg_match('#^/api/forms/(?P<id>\\d+)/delete$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_forms_delete']), array (  '_controller' => 'Mautic\\FormBundle\\Controller\\Api\\FormApiController::deleteEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_forms_delete;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_forms_delete;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_forms_delete', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_forms_delete:

                    // mautic_api_formresults
                    if (preg_match('#^/api/forms/(?P<formId>\\d+)/submissions$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_formresults']), array (  '_controller' => 'Mautic\\FormBundle\\Controller\\Api\\SubmissionApiController::getEntitiesAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_formresults;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_formresults;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_formresults', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_formresults:

                    // mautic_api_formresult
                    if (preg_match('#^/api/forms/(?P<formId>\\d+)/submissions/(?P<submissionId>\\d+)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_formresult']), array (  '_controller' => 'Mautic\\FormBundle\\Controller\\Api\\SubmissionApiController::getEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_formresult;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_formresult;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_formresult', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_formresult:

                    // mautic_api_contactformresults
                    if (preg_match('#^/api/forms/(?P<formId>\\d+)/submissions/contact/(?P<contactId>\\d+)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_contactformresults']), array (  '_controller' => 'Mautic\\FormBundle\\Controller\\Api\\SubmissionApiController::getEntitiesForContactAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_contactformresults;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_contactformresults;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_contactformresults', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_contactformresults:

                    // mautic_api_formdeletefields
                    if (preg_match('#^/api/forms/(?P<formId>\\d+)/fields/delete$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_formdeletefields']), array (  '_controller' => 'Mautic\\FormBundle\\Controller\\Api\\FormApiController::deleteFieldsAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_formdeletefields;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_formdeletefields;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_formdeletefields', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_formdeletefields:

                    // mautic_api_formdeleteactions
                    if (preg_match('#^/api/forms/(?P<formId>\\d+)/actions/delete$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_formdeleteactions']), array (  '_controller' => 'Mautic\\FormBundle\\Controller\\Api\\FormApiController::deleteActionsAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_formdeleteactions;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_formdeleteactions;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_formdeleteactions', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_formdeleteactions:

                }

                elseif (0 === strpos($pathinfo, '/api/focus')) {
                    // mautic_api_focus_getall
                    if ('/api/focus' === $pathinfo) {
                        $ret = array (  '_controller' => 'MauticPlugin\\MauticFocusBundle\\Controller\\Api\\FocusApiController::getEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_focus_getall',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_focus_getall;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_focus_getall;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_focus_getall', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_focus_getall:

                    // mautic_api_focus_getone
                    if (preg_match('#^/api/focus/(?P<id>\\d+)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_focus_getone']), array (  '_controller' => 'MauticPlugin\\MauticFocusBundle\\Controller\\Api\\FocusApiController::getEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_focus_getone;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_focus_getone;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_focus_getone', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_focus_getone:

                    // mautic_api_focus_new
                    if ('/api/focus/new' === $pathinfo) {
                        $ret = array (  '_controller' => 'MauticPlugin\\MauticFocusBundle\\Controller\\Api\\FocusApiController::newEntityAction',  '_format' => 'json',  '_route' => 'mautic_api_focus_new',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_focus_new;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_focus_new;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_focus_new', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_focus_new:

                    // mautic_api_focus_newbatch
                    if ('/api/focus/batch/new' === $pathinfo) {
                        $ret = array (  '_controller' => 'MauticPlugin\\MauticFocusBundle\\Controller\\Api\\FocusApiController::newEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_focus_newbatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_focus_newbatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_focus_newbatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_focus_newbatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_focus_newbatch:

                    if (0 === strpos($pathinfo, '/api/focus/batch/edit')) {
                        // mautic_api_focus_editbatchput
                        if ('/api/focus/batch/edit' === $pathinfo) {
                            $ret = array (  '_controller' => 'MauticPlugin\\MauticFocusBundle\\Controller\\Api\\FocusApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_focus_editbatchput',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($requestMethod, ['PUT'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['PUT']);
                                }
                                goto not_mautic_api_focus_editbatchput;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_focus_editbatchput;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_focus_editbatchput', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_focus_editbatchput:

                        // mautic_api_focus_editbatchpatch
                        if ('/api/focus/batch/edit' === $pathinfo) {
                            $ret = array (  '_controller' => 'MauticPlugin\\MauticFocusBundle\\Controller\\Api\\FocusApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_focus_editbatchpatch',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($requestMethod, ['PATCH'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['PATCH']);
                                }
                                goto not_mautic_api_focus_editbatchpatch;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_focus_editbatchpatch;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_focus_editbatchpatch', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_focus_editbatchpatch:

                    }

                    // mautic_api_focus_editput
                    if (preg_match('#^/api/focus/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_focus_editput']), array (  '_controller' => 'MauticPlugin\\MauticFocusBundle\\Controller\\Api\\FocusApiController::editEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PUT'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PUT']);
                            }
                            goto not_mautic_api_focus_editput;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_focus_editput;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_focus_editput', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_focus_editput:

                    // mautic_api_focus_editpatch
                    if (preg_match('#^/api/focus/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_focus_editpatch']), array (  '_controller' => 'MauticPlugin\\MauticFocusBundle\\Controller\\Api\\FocusApiController::editEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PATCH'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PATCH']);
                            }
                            goto not_mautic_api_focus_editpatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_focus_editpatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_focus_editpatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_focus_editpatch:

                    // mautic_api_focus_deletebatch
                    if ('/api/focus/batch/delete' === $pathinfo) {
                        $ret = array (  '_controller' => 'MauticPlugin\\MauticFocusBundle\\Controller\\Api\\FocusApiController::deleteEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_focus_deletebatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_focus_deletebatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_focus_deletebatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_focus_deletebatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_focus_deletebatch:

                    // mautic_api_focus_delete
                    if (preg_match('#^/api/focus/(?P<id>\\d+)/delete$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_focus_delete']), array (  '_controller' => 'MauticPlugin\\MauticFocusBundle\\Controller\\Api\\FocusApiController::deleteEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_focus_delete;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_focus_delete;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_focus_delete', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_focus_delete:

                    // mautic_api_focusjs
                    if (preg_match('#^/api/focus/(?P<id>\\d+)/js$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_focusjs']), array (  '_controller' => 'MauticPlugin\\MauticFocusBundle\\Controller\\Api\\FocusApiController::generateJsAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_focusjs;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_focusjs;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_focusjs', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_focusjs:

                }

            }

            elseif (0 === strpos($pathinfo, '/api/t')) {
                if (0 === strpos($pathinfo, '/api/themes')) {
                    // mautic_core_api_theme_list
                    if ('/api/themes' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\CoreBundle\\Controller\\Api\\ThemeApiController::listAction',  '_format' => 'json',  '_route' => 'mautic_core_api_theme_list',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_core_api_theme_list;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_core_api_theme_list;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_core_api_theme_list', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_core_api_theme_list:

                    // mautic_core_api_theme_get
                    if (preg_match('#^/api/themes/(?P<theme>[^/]++)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_core_api_theme_get']), array (  '_controller' => 'Mautic\\CoreBundle\\Controller\\Api\\ThemeApiController::getAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_core_api_theme_get;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_core_api_theme_get;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_core_api_theme_get', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_core_api_theme_get:

                    // mautic_core_api_theme_create
                    if ('/api/themes/new' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\CoreBundle\\Controller\\Api\\ThemeApiController::newAction',  '_format' => 'json',  '_route' => 'mautic_core_api_theme_create',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_core_api_theme_create;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_core_api_theme_create;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_core_api_theme_create', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_core_api_theme_create:

                    // mautic_core_api_theme_delete
                    if (preg_match('#^/api/themes/(?P<theme>[^/]++)/delete$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_core_api_theme_delete']), array (  '_controller' => 'Mautic\\CoreBundle\\Controller\\Api\\ThemeApiController::deleteAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_core_api_theme_delete;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_core_api_theme_delete;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_core_api_theme_delete', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_core_api_theme_delete:

                }

                elseif (0 === strpos($pathinfo, '/api/tags')) {
                    // mautic_api_tags_getall
                    if ('/api/tags' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\TagApiController::getEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_tags_getall',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_tags_getall;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_tags_getall;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_tags_getall', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_tags_getall:

                    // mautic_api_tags_getone
                    if (preg_match('#^/api/tags/(?P<id>\\d+)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_tags_getone']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\TagApiController::getEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_tags_getone;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_tags_getone;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_tags_getone', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_tags_getone:

                    // mautic_api_tags_new
                    if ('/api/tags/new' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\TagApiController::newEntityAction',  '_format' => 'json',  '_route' => 'mautic_api_tags_new',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_tags_new;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_tags_new;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_tags_new', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_tags_new:

                    // mautic_api_tags_newbatch
                    if ('/api/tags/batch/new' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\TagApiController::newEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_tags_newbatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_tags_newbatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_tags_newbatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_tags_newbatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_tags_newbatch:

                    if (0 === strpos($pathinfo, '/api/tags/batch/edit')) {
                        // mautic_api_tags_editbatchput
                        if ('/api/tags/batch/edit' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\TagApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_tags_editbatchput',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($requestMethod, ['PUT'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['PUT']);
                                }
                                goto not_mautic_api_tags_editbatchput;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_tags_editbatchput;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_tags_editbatchput', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_tags_editbatchput:

                        // mautic_api_tags_editbatchpatch
                        if ('/api/tags/batch/edit' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\TagApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_tags_editbatchpatch',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($requestMethod, ['PATCH'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['PATCH']);
                                }
                                goto not_mautic_api_tags_editbatchpatch;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_tags_editbatchpatch;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_tags_editbatchpatch', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_tags_editbatchpatch:

                    }

                    // mautic_api_tags_editput
                    if (preg_match('#^/api/tags/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_tags_editput']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\TagApiController::editEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PUT'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PUT']);
                            }
                            goto not_mautic_api_tags_editput;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_tags_editput;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_tags_editput', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_tags_editput:

                    // mautic_api_tags_editpatch
                    if (preg_match('#^/api/tags/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_tags_editpatch']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\TagApiController::editEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PATCH'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PATCH']);
                            }
                            goto not_mautic_api_tags_editpatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_tags_editpatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_tags_editpatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_tags_editpatch:

                    // mautic_api_tags_deletebatch
                    if ('/api/tags/batch/delete' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\TagApiController::deleteEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_tags_deletebatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_tags_deletebatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_tags_deletebatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_tags_deletebatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_tags_deletebatch:

                    // mautic_api_tags_delete
                    if (preg_match('#^/api/tags/(?P<id>\\d+)/delete$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_tags_delete']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\TagApiController::deleteEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_tags_delete;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_tags_delete;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_tags_delete', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_tags_delete:

                }

                elseif (0 === strpos($pathinfo, '/api/tweets')) {
                    // mautic_api_tweets_getall
                    if ('/api/tweets' === $pathinfo) {
                        $ret = array (  '_controller' => 'MauticPlugin\\MauticSocialBundle\\Controller\\Api\\TweetApiController::getEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_tweets_getall',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_tweets_getall;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_tweets_getall;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_tweets_getall', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_tweets_getall:

                    // mautic_api_tweets_getone
                    if (preg_match('#^/api/tweets/(?P<id>\\d+)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_tweets_getone']), array (  '_controller' => 'MauticPlugin\\MauticSocialBundle\\Controller\\Api\\TweetApiController::getEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_tweets_getone;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_tweets_getone;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_tweets_getone', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_tweets_getone:

                    // mautic_api_tweets_new
                    if ('/api/tweets/new' === $pathinfo) {
                        $ret = array (  '_controller' => 'MauticPlugin\\MauticSocialBundle\\Controller\\Api\\TweetApiController::newEntityAction',  '_format' => 'json',  '_route' => 'mautic_api_tweets_new',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_tweets_new;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_tweets_new;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_tweets_new', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_tweets_new:

                    // mautic_api_tweets_newbatch
                    if ('/api/tweets/batch/new' === $pathinfo) {
                        $ret = array (  '_controller' => 'MauticPlugin\\MauticSocialBundle\\Controller\\Api\\TweetApiController::newEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_tweets_newbatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_tweets_newbatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_tweets_newbatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_tweets_newbatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_tweets_newbatch:

                    if (0 === strpos($pathinfo, '/api/tweets/batch/edit')) {
                        // mautic_api_tweets_editbatchput
                        if ('/api/tweets/batch/edit' === $pathinfo) {
                            $ret = array (  '_controller' => 'MauticPlugin\\MauticSocialBundle\\Controller\\Api\\TweetApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_tweets_editbatchput',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($requestMethod, ['PUT'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['PUT']);
                                }
                                goto not_mautic_api_tweets_editbatchput;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_tweets_editbatchput;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_tweets_editbatchput', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_tweets_editbatchput:

                        // mautic_api_tweets_editbatchpatch
                        if ('/api/tweets/batch/edit' === $pathinfo) {
                            $ret = array (  '_controller' => 'MauticPlugin\\MauticSocialBundle\\Controller\\Api\\TweetApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_tweets_editbatchpatch',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($requestMethod, ['PATCH'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['PATCH']);
                                }
                                goto not_mautic_api_tweets_editbatchpatch;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_tweets_editbatchpatch;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_tweets_editbatchpatch', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_tweets_editbatchpatch:

                    }

                    // mautic_api_tweets_editput
                    if (preg_match('#^/api/tweets/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_tweets_editput']), array (  '_controller' => 'MauticPlugin\\MauticSocialBundle\\Controller\\Api\\TweetApiController::editEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PUT'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PUT']);
                            }
                            goto not_mautic_api_tweets_editput;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_tweets_editput;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_tweets_editput', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_tweets_editput:

                    // mautic_api_tweets_editpatch
                    if (preg_match('#^/api/tweets/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_tweets_editpatch']), array (  '_controller' => 'MauticPlugin\\MauticSocialBundle\\Controller\\Api\\TweetApiController::editEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PATCH'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PATCH']);
                            }
                            goto not_mautic_api_tweets_editpatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_tweets_editpatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_tweets_editpatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_tweets_editpatch:

                    // mautic_api_tweets_deletebatch
                    if ('/api/tweets/batch/delete' === $pathinfo) {
                        $ret = array (  '_controller' => 'MauticPlugin\\MauticSocialBundle\\Controller\\Api\\TweetApiController::deleteEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_tweets_deletebatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_tweets_deletebatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_tweets_deletebatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_tweets_deletebatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_tweets_deletebatch:

                    // mautic_api_tweets_delete
                    if (preg_match('#^/api/tweets/(?P<id>\\d+)/delete$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_tweets_delete']), array (  '_controller' => 'MauticPlugin\\MauticSocialBundle\\Controller\\Api\\TweetApiController::deleteEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_tweets_delete;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_tweets_delete;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_tweets_delete', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_tweets_delete:

                }

            }

            elseif (0 === strpos($pathinfo, '/api/s')) {
                // mautic_core_api_stats
                if (0 === strpos($pathinfo, '/api/stats') && preg_match('#^/api/stats(?:/(?P<table>[^/]++))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_core_api_stats']), array (  'table' => '',  '_controller' => 'Mautic\\CoreBundle\\Controller\\Api\\StatsApiController::listAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($canonicalMethod, ['GET'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['GET']);
                        }
                        goto not_mautic_core_api_stats;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_core_api_stats;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_core_api_stats', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_core_api_stats:

                if (0 === strpos($pathinfo, '/api/stages')) {
                    // mautic_api_stages_getall
                    if ('/api/stages' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\StageBundle\\Controller\\Api\\StageApiController::getEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_stages_getall',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_stages_getall;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_stages_getall;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_stages_getall', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_stages_getall:

                    // mautic_api_stages_getone
                    if (preg_match('#^/api/stages/(?P<id>\\d+)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_stages_getone']), array (  '_controller' => 'Mautic\\StageBundle\\Controller\\Api\\StageApiController::getEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_stages_getone;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_stages_getone;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_stages_getone', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_stages_getone:

                    // mautic_api_stages_new
                    if ('/api/stages/new' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\StageBundle\\Controller\\Api\\StageApiController::newEntityAction',  '_format' => 'json',  '_route' => 'mautic_api_stages_new',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_stages_new;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_stages_new;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_stages_new', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_stages_new:

                    // mautic_api_stages_newbatch
                    if ('/api/stages/batch/new' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\StageBundle\\Controller\\Api\\StageApiController::newEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_stages_newbatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_stages_newbatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_stages_newbatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_stages_newbatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_stages_newbatch:

                    if (0 === strpos($pathinfo, '/api/stages/batch/edit')) {
                        // mautic_api_stages_editbatchput
                        if ('/api/stages/batch/edit' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\StageBundle\\Controller\\Api\\StageApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_stages_editbatchput',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($requestMethod, ['PUT'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['PUT']);
                                }
                                goto not_mautic_api_stages_editbatchput;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_stages_editbatchput;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_stages_editbatchput', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_stages_editbatchput:

                        // mautic_api_stages_editbatchpatch
                        if ('/api/stages/batch/edit' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\StageBundle\\Controller\\Api\\StageApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_stages_editbatchpatch',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($requestMethod, ['PATCH'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['PATCH']);
                                }
                                goto not_mautic_api_stages_editbatchpatch;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_stages_editbatchpatch;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_stages_editbatchpatch', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_stages_editbatchpatch:

                    }

                    // mautic_api_stages_editput
                    if (preg_match('#^/api/stages/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_stages_editput']), array (  '_controller' => 'Mautic\\StageBundle\\Controller\\Api\\StageApiController::editEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PUT'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PUT']);
                            }
                            goto not_mautic_api_stages_editput;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_stages_editput;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_stages_editput', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_stages_editput:

                    // mautic_api_stages_editpatch
                    if (preg_match('#^/api/stages/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_stages_editpatch']), array (  '_controller' => 'Mautic\\StageBundle\\Controller\\Api\\StageApiController::editEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PATCH'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PATCH']);
                            }
                            goto not_mautic_api_stages_editpatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_stages_editpatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_stages_editpatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_stages_editpatch:

                    // mautic_api_stages_deletebatch
                    if ('/api/stages/batch/delete' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\StageBundle\\Controller\\Api\\StageApiController::deleteEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_stages_deletebatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_stages_deletebatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_stages_deletebatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_stages_deletebatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_stages_deletebatch:

                    // mautic_api_stages_delete
                    if (preg_match('#^/api/stages/(?P<id>\\d+)/delete$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_stages_delete']), array (  '_controller' => 'Mautic\\StageBundle\\Controller\\Api\\StageApiController::deleteEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_stages_delete;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_stages_delete;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_stages_delete', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_stages_delete:

                    // mautic_api_stageddcontact
                    if (preg_match('#^/api/stages/(?P<id>\\d+)/contact/(?P<contactId>[^/]++)/add$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_stageddcontact']), array (  '_controller' => 'Mautic\\StageBundle\\Controller\\Api\\StageApiController::addContactAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_stageddcontact;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_stageddcontact;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_stageddcontact', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_stageddcontact:

                    // mautic_api_stageremovecontact
                    if (preg_match('#^/api/stages/(?P<id>\\d+)/contact/(?P<contactId>[^/]++)/remove$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_stageremovecontact']), array (  '_controller' => 'Mautic\\StageBundle\\Controller\\Api\\StageApiController::removeContactAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_stageremovecontact;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_stageremovecontact;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_stageremovecontact', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_stageremovecontact:

                }

                elseif (0 === strpos($pathinfo, '/api/segments')) {
                    // mautic_api_lists_getall
                    if ('/api/segments' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\ListApiController::getEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_lists_getall',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_lists_getall;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_lists_getall;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_lists_getall', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_lists_getall:

                    // mautic_api_lists_getone
                    if (preg_match('#^/api/segments/(?P<id>\\d+)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_lists_getone']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\ListApiController::getEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_lists_getone;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_lists_getone;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_lists_getone', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_lists_getone:

                    // mautic_api_lists_new
                    if ('/api/segments/new' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\ListApiController::newEntityAction',  '_format' => 'json',  '_route' => 'mautic_api_lists_new',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_lists_new;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_lists_new;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_lists_new', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_lists_new:

                    // mautic_api_lists_newbatch
                    if ('/api/segments/batch/new' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\ListApiController::newEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_lists_newbatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_lists_newbatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_lists_newbatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_lists_newbatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_lists_newbatch:

                    if (0 === strpos($pathinfo, '/api/segments/batch/edit')) {
                        // mautic_api_lists_editbatchput
                        if ('/api/segments/batch/edit' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\ListApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_lists_editbatchput',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($requestMethod, ['PUT'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['PUT']);
                                }
                                goto not_mautic_api_lists_editbatchput;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_lists_editbatchput;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_lists_editbatchput', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_lists_editbatchput:

                        // mautic_api_lists_editbatchpatch
                        if ('/api/segments/batch/edit' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\ListApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_lists_editbatchpatch',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($requestMethod, ['PATCH'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['PATCH']);
                                }
                                goto not_mautic_api_lists_editbatchpatch;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_lists_editbatchpatch;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_lists_editbatchpatch', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_lists_editbatchpatch:

                    }

                    // mautic_api_lists_editput
                    if (preg_match('#^/api/segments/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_lists_editput']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\ListApiController::editEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PUT'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PUT']);
                            }
                            goto not_mautic_api_lists_editput;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_lists_editput;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_lists_editput', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_lists_editput:

                    // mautic_api_lists_editpatch
                    if (preg_match('#^/api/segments/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_lists_editpatch']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\ListApiController::editEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PATCH'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PATCH']);
                            }
                            goto not_mautic_api_lists_editpatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_lists_editpatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_lists_editpatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_lists_editpatch:

                    // mautic_api_lists_deletebatch
                    if ('/api/segments/batch/delete' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\ListApiController::deleteEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_lists_deletebatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_lists_deletebatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_lists_deletebatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_lists_deletebatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_lists_deletebatch:

                    // mautic_api_lists_delete
                    if (preg_match('#^/api/segments/(?P<id>\\d+)/delete$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_lists_delete']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\ListApiController::deleteEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_lists_delete;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_lists_delete;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_lists_delete', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_lists_delete:

                    // mautic_api_segmentaddcontact
                    if (preg_match('#^/api/segments/(?P<id>\\d+)/contact/(?P<leadId>[^/]++)/add$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_segmentaddcontact']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\ListApiController::addLeadAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_segmentaddcontact;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_segmentaddcontact;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_segmentaddcontact', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_segmentaddcontact:

                    // mautic_api_segmentaddcontacts
                    if (preg_match('#^/api/segments/(?P<id>\\d+)/contacts/add$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_segmentaddcontacts']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\ListApiController::addLeadsAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_segmentaddcontacts;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_segmentaddcontacts;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_segmentaddcontacts', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_segmentaddcontacts:

                    // mautic_api_segmentremovecontact
                    if (preg_match('#^/api/segments/(?P<id>\\d+)/contact/(?P<leadId>[^/]++)/remove$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_segmentremovecontact']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\ListApiController::removeLeadAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_segmentremovecontact;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_segmentremovecontact;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_segmentremovecontact', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_segmentremovecontact:

                }

                elseif (0 === strpos($pathinfo, '/api/smses')) {
                    // mautic_api_smses_getall
                    if ('/api/smses' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\SmsBundle\\Controller\\Api\\SmsApiController::getEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_smses_getall',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_smses_getall;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_smses_getall;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_smses_getall', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_smses_getall:

                    // mautic_api_smses_getone
                    if (preg_match('#^/api/smses/(?P<id>\\d+)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_smses_getone']), array (  '_controller' => 'Mautic\\SmsBundle\\Controller\\Api\\SmsApiController::getEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_smses_getone;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_smses_getone;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_smses_getone', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_smses_getone:

                    // mautic_api_smses_new
                    if ('/api/smses/new' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\SmsBundle\\Controller\\Api\\SmsApiController::newEntityAction',  '_format' => 'json',  '_route' => 'mautic_api_smses_new',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_smses_new;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_smses_new;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_smses_new', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_smses_new:

                    // mautic_api_smses_newbatch
                    if ('/api/smses/batch/new' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\SmsBundle\\Controller\\Api\\SmsApiController::newEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_smses_newbatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_smses_newbatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_smses_newbatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_smses_newbatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_smses_newbatch:

                    if (0 === strpos($pathinfo, '/api/smses/batch/edit')) {
                        // mautic_api_smses_editbatchput
                        if ('/api/smses/batch/edit' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\SmsBundle\\Controller\\Api\\SmsApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_smses_editbatchput',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($requestMethod, ['PUT'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['PUT']);
                                }
                                goto not_mautic_api_smses_editbatchput;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_smses_editbatchput;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_smses_editbatchput', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_smses_editbatchput:

                        // mautic_api_smses_editbatchpatch
                        if ('/api/smses/batch/edit' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\SmsBundle\\Controller\\Api\\SmsApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_smses_editbatchpatch',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($requestMethod, ['PATCH'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['PATCH']);
                                }
                                goto not_mautic_api_smses_editbatchpatch;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_smses_editbatchpatch;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_smses_editbatchpatch', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_smses_editbatchpatch:

                    }

                    // mautic_api_smses_editput
                    if (preg_match('#^/api/smses/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_smses_editput']), array (  '_controller' => 'Mautic\\SmsBundle\\Controller\\Api\\SmsApiController::editEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PUT'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PUT']);
                            }
                            goto not_mautic_api_smses_editput;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_smses_editput;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_smses_editput', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_smses_editput:

                    // mautic_api_smses_editpatch
                    if (preg_match('#^/api/smses/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_smses_editpatch']), array (  '_controller' => 'Mautic\\SmsBundle\\Controller\\Api\\SmsApiController::editEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PATCH'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PATCH']);
                            }
                            goto not_mautic_api_smses_editpatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_smses_editpatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_smses_editpatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_smses_editpatch:

                    // mautic_api_smses_deletebatch
                    if ('/api/smses/batch/delete' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\SmsBundle\\Controller\\Api\\SmsApiController::deleteEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_smses_deletebatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_smses_deletebatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_smses_deletebatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_smses_deletebatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_smses_deletebatch:

                    // mautic_api_smses_delete
                    if (preg_match('#^/api/smses/(?P<id>\\d+)/delete$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_smses_delete']), array (  '_controller' => 'Mautic\\SmsBundle\\Controller\\Api\\SmsApiController::deleteEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_smses_delete;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_smses_delete;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_smses_delete', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_smses_delete:

                    // mautic_api_smses_send
                    if (preg_match('#^/api/smses/(?P<id>\\d+)/contact/(?P<contactId>[^/]++)/send$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_smses_send']), array (  '_controller' => 'Mautic\\SmsBundle\\Controller\\Api\\SmsApiController::sendAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_smses_send;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_smses_send;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_smses_send', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_smses_send:

                }

            }

            elseif (0 === strpos($pathinfo, '/api/assets')) {
                // mautic_api_assets_getall
                if ('/api/assets' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\AssetBundle\\Controller\\Api\\AssetApiController::getEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_assets_getall',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($canonicalMethod, ['GET'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['GET']);
                        }
                        goto not_mautic_api_assets_getall;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_assets_getall;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_assets_getall', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_assets_getall:

                // mautic_api_assets_getone
                if (preg_match('#^/api/assets/(?P<id>\\d+)$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_assets_getone']), array (  '_controller' => 'Mautic\\AssetBundle\\Controller\\Api\\AssetApiController::getEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($canonicalMethod, ['GET'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['GET']);
                        }
                        goto not_mautic_api_assets_getone;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_assets_getone;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_assets_getone', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_assets_getone:

                // mautic_api_assets_new
                if ('/api/assets/new' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\AssetBundle\\Controller\\Api\\AssetApiController::newEntityAction',  '_format' => 'json',  '_route' => 'mautic_api_assets_new',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['POST'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['POST']);
                        }
                        goto not_mautic_api_assets_new;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_assets_new;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_assets_new', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_assets_new:

                // mautic_api_assets_newbatch
                if ('/api/assets/batch/new' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\AssetBundle\\Controller\\Api\\AssetApiController::newEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_assets_newbatch',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['POST'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['POST']);
                        }
                        goto not_mautic_api_assets_newbatch;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_assets_newbatch;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_assets_newbatch', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_assets_newbatch:

                if (0 === strpos($pathinfo, '/api/assets/batch/edit')) {
                    // mautic_api_assets_editbatchput
                    if ('/api/assets/batch/edit' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\AssetBundle\\Controller\\Api\\AssetApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_assets_editbatchput',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PUT'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PUT']);
                            }
                            goto not_mautic_api_assets_editbatchput;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_assets_editbatchput;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_assets_editbatchput', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_assets_editbatchput:

                    // mautic_api_assets_editbatchpatch
                    if ('/api/assets/batch/edit' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\AssetBundle\\Controller\\Api\\AssetApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_assets_editbatchpatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PATCH'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PATCH']);
                            }
                            goto not_mautic_api_assets_editbatchpatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_assets_editbatchpatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_assets_editbatchpatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_assets_editbatchpatch:

                }

                // mautic_api_assets_editput
                if (preg_match('#^/api/assets/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_assets_editput']), array (  '_controller' => 'Mautic\\AssetBundle\\Controller\\Api\\AssetApiController::editEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['PUT'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['PUT']);
                        }
                        goto not_mautic_api_assets_editput;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_assets_editput;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_assets_editput', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_assets_editput:

                // mautic_api_assets_editpatch
                if (preg_match('#^/api/assets/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_assets_editpatch']), array (  '_controller' => 'Mautic\\AssetBundle\\Controller\\Api\\AssetApiController::editEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['PATCH'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['PATCH']);
                        }
                        goto not_mautic_api_assets_editpatch;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_assets_editpatch;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_assets_editpatch', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_assets_editpatch:

                // mautic_api_assets_deletebatch
                if ('/api/assets/batch/delete' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\AssetBundle\\Controller\\Api\\AssetApiController::deleteEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_assets_deletebatch',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['DELETE'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['DELETE']);
                        }
                        goto not_mautic_api_assets_deletebatch;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_assets_deletebatch;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_assets_deletebatch', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_assets_deletebatch:

                // mautic_api_assets_delete
                if (preg_match('#^/api/assets/(?P<id>\\d+)/delete$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_assets_delete']), array (  '_controller' => 'Mautic\\AssetBundle\\Controller\\Api\\AssetApiController::deleteEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['DELETE'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['DELETE']);
                        }
                        goto not_mautic_api_assets_delete;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_assets_delete;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_assets_delete', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_assets_delete:

            }

            elseif (0 === strpos($pathinfo, '/api/c')) {
                if (0 === strpos($pathinfo, '/api/campaigns')) {
                    // mautic_api_campaigns_getall
                    if ('/api/campaigns' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\CampaignBundle\\Controller\\Api\\CampaignApiController::getEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_campaigns_getall',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_campaigns_getall;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_campaigns_getall;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_campaigns_getall', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_campaigns_getall:

                    // mautic_api_campaigns_getone
                    if (preg_match('#^/api/campaigns/(?P<id>\\d+)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_campaigns_getone']), array (  '_controller' => 'Mautic\\CampaignBundle\\Controller\\Api\\CampaignApiController::getEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_campaigns_getone;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_campaigns_getone;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_campaigns_getone', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_campaigns_getone:

                    // mautic_api_campaigns_new
                    if ('/api/campaigns/new' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\CampaignBundle\\Controller\\Api\\CampaignApiController::newEntityAction',  '_format' => 'json',  '_route' => 'mautic_api_campaigns_new',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_campaigns_new;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_campaigns_new;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_campaigns_new', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_campaigns_new:

                    // mautic_api_campaigns_newbatch
                    if ('/api/campaigns/batch/new' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\CampaignBundle\\Controller\\Api\\CampaignApiController::newEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_campaigns_newbatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_campaigns_newbatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_campaigns_newbatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_campaigns_newbatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_campaigns_newbatch:

                    if (0 === strpos($pathinfo, '/api/campaigns/batch/edit')) {
                        // mautic_api_campaigns_editbatchput
                        if ('/api/campaigns/batch/edit' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\CampaignBundle\\Controller\\Api\\CampaignApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_campaigns_editbatchput',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($requestMethod, ['PUT'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['PUT']);
                                }
                                goto not_mautic_api_campaigns_editbatchput;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_campaigns_editbatchput;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_campaigns_editbatchput', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_campaigns_editbatchput:

                        // mautic_api_campaigns_editbatchpatch
                        if ('/api/campaigns/batch/edit' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\CampaignBundle\\Controller\\Api\\CampaignApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_campaigns_editbatchpatch',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($requestMethod, ['PATCH'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['PATCH']);
                                }
                                goto not_mautic_api_campaigns_editbatchpatch;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_campaigns_editbatchpatch;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_campaigns_editbatchpatch', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_campaigns_editbatchpatch:

                    }

                    // mautic_api_campaigns_editput
                    if (preg_match('#^/api/campaigns/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_campaigns_editput']), array (  '_controller' => 'Mautic\\CampaignBundle\\Controller\\Api\\CampaignApiController::editEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PUT'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PUT']);
                            }
                            goto not_mautic_api_campaigns_editput;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_campaigns_editput;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_campaigns_editput', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_campaigns_editput:

                    // mautic_api_campaigns_editpatch
                    if (preg_match('#^/api/campaigns/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_campaigns_editpatch']), array (  '_controller' => 'Mautic\\CampaignBundle\\Controller\\Api\\CampaignApiController::editEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PATCH'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PATCH']);
                            }
                            goto not_mautic_api_campaigns_editpatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_campaigns_editpatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_campaigns_editpatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_campaigns_editpatch:

                    // mautic_api_campaigns_deletebatch
                    if ('/api/campaigns/batch/delete' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\CampaignBundle\\Controller\\Api\\CampaignApiController::deleteEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_campaigns_deletebatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_campaigns_deletebatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_campaigns_deletebatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_campaigns_deletebatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_campaigns_deletebatch:

                    // mautic_api_campaigns_delete
                    if (preg_match('#^/api/campaigns/(?P<id>\\d+)/delete$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_campaigns_delete']), array (  '_controller' => 'Mautic\\CampaignBundle\\Controller\\Api\\CampaignApiController::deleteEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_campaigns_delete;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_campaigns_delete;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_campaigns_delete', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_campaigns_delete:

                    if (0 === strpos($pathinfo, '/api/campaigns/events')) {
                        // mautic_api_events_getall
                        if ('/api/campaigns/events' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\CampaignBundle\\Controller\\Api\\EventApiController::getEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_events_getall',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($canonicalMethod, ['GET'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['GET']);
                                }
                                goto not_mautic_api_events_getall;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_events_getall;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_events_getall', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_events_getall:

                        // mautic_api_events_getone
                        if (preg_match('#^/api/campaigns/events/(?P<id>\\d+)$#sD', $pathinfo, $matches)) {
                            $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_events_getone']), array (  '_controller' => 'Mautic\\CampaignBundle\\Controller\\Api\\EventApiController::getEntityAction',  '_format' => 'json',));
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($canonicalMethod, ['GET'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['GET']);
                                }
                                goto not_mautic_api_events_getone;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_events_getone;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_events_getone', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_events_getone:

                        // mautic_api_campaigns_events_contact
                        if (0 === strpos($pathinfo, '/api/campaigns/events/contact') && preg_match('#^/api/campaigns/events/contact/(?P<contactId>\\d+)$#sD', $pathinfo, $matches)) {
                            $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_campaigns_events_contact']), array (  '_controller' => 'Mautic\\CampaignBundle\\Controller\\Api\\EventLogApiController::getContactEventsAction',  '_format' => 'json',));
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($canonicalMethod, ['GET'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['GET']);
                                }
                                goto not_mautic_api_campaigns_events_contact;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_campaigns_events_contact;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_campaigns_events_contact', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_campaigns_events_contact:

                        // mautic_api_campaigns_edit_contact_event
                        if (preg_match('#^/api/campaigns/events/(?P<eventId>\\d+)/contact/(?P<contactId>\\d+)/edit$#sD', $pathinfo, $matches)) {
                            $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_campaigns_edit_contact_event']), array (  '_controller' => 'Mautic\\CampaignBundle\\Controller\\Api\\EventLogApiController::editContactEventAction',  '_format' => 'json',));
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($requestMethod, ['PUT'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['PUT']);
                                }
                                goto not_mautic_api_campaigns_edit_contact_event;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_campaigns_edit_contact_event;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_campaigns_edit_contact_event', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_campaigns_edit_contact_event:

                        // mautic_api_campaigns_batchedit_events
                        if ('/api/campaigns/events/batch/edit' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\CampaignBundle\\Controller\\Api\\EventLogApiController::editEventsAction',  '_format' => 'json',  '_route' => 'mautic_api_campaigns_batchedit_events',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($requestMethod, ['PUT'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['PUT']);
                                }
                                goto not_mautic_api_campaigns_batchedit_events;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_campaigns_batchedit_events;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_campaigns_batchedit_events', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_campaigns_batchedit_events:

                    }

                    // mautic_api_campaign_contact_events
                    if (preg_match('#^/api/campaigns/(?P<campaignId>\\d+)/events/contact/(?P<contactId>\\d+)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_campaign_contact_events']), array (  '_controller' => 'Mautic\\CampaignBundle\\Controller\\Api\\EventLogApiController::getContactEventsAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_campaign_contact_events;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_campaign_contact_events;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_campaign_contact_events', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_campaign_contact_events:

                    // mautic_api_campaigngetcontacts
                    if (preg_match('#^/api/campaigns/(?P<id>\\d+)/contacts$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_campaigngetcontacts']), array (  '_controller' => 'Mautic\\CampaignBundle\\Controller\\Api\\CampaignApiController::getContactsAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_campaigngetcontacts;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_campaigngetcontacts;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_campaigngetcontacts', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_campaigngetcontacts:

                    // mautic_api_campaignaddcontact
                    if (preg_match('#^/api/campaigns/(?P<id>\\d+)/contact/(?P<leadId>[^/]++)/add$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_campaignaddcontact']), array (  '_controller' => 'Mautic\\CampaignBundle\\Controller\\Api\\CampaignApiController::addLeadAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_campaignaddcontact;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_campaignaddcontact;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_campaignaddcontact', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_campaignaddcontact:

                    // mautic_api_campaignremovecontact
                    if (preg_match('#^/api/campaigns/(?P<id>\\d+)/contact/(?P<leadId>[^/]++)/remove$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_campaignremovecontact']), array (  '_controller' => 'Mautic\\CampaignBundle\\Controller\\Api\\CampaignApiController::removeLeadAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_campaignremovecontact;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_campaignremovecontact;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_campaignremovecontact', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_campaignremovecontact:

                    // mautic_api_contact_clone_campaign
                    if (0 === strpos($pathinfo, '/api/campaigns/clone') && preg_match('#^/api/campaigns/clone/(?P<campaignId>\\d+)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_contact_clone_campaign']), array (  '_controller' => 'Mautic\\CampaignBundle\\Controller\\Api\\CampaignApiController::cloneCampaignAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_contact_clone_campaign;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_contact_clone_campaign;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_contact_clone_campaign', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_contact_clone_campaign:

                }

                elseif (0 === strpos($pathinfo, '/api/categories')) {
                    // mautic_api_categories_getall
                    if ('/api/categories' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\CategoryBundle\\Controller\\Api\\CategoryApiController::getEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_categories_getall',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_categories_getall;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_categories_getall;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_categories_getall', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_categories_getall:

                    // mautic_api_categories_getone
                    if (preg_match('#^/api/categories/(?P<id>\\d+)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_categories_getone']), array (  '_controller' => 'Mautic\\CategoryBundle\\Controller\\Api\\CategoryApiController::getEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_categories_getone;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_categories_getone;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_categories_getone', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_categories_getone:

                    // mautic_api_categories_new
                    if ('/api/categories/new' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\CategoryBundle\\Controller\\Api\\CategoryApiController::newEntityAction',  '_format' => 'json',  '_route' => 'mautic_api_categories_new',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_categories_new;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_categories_new;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_categories_new', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_categories_new:

                    // mautic_api_categories_newbatch
                    if ('/api/categories/batch/new' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\CategoryBundle\\Controller\\Api\\CategoryApiController::newEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_categories_newbatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_categories_newbatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_categories_newbatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_categories_newbatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_categories_newbatch:

                    if (0 === strpos($pathinfo, '/api/categories/batch/edit')) {
                        // mautic_api_categories_editbatchput
                        if ('/api/categories/batch/edit' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\CategoryBundle\\Controller\\Api\\CategoryApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_categories_editbatchput',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($requestMethod, ['PUT'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['PUT']);
                                }
                                goto not_mautic_api_categories_editbatchput;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_categories_editbatchput;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_categories_editbatchput', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_categories_editbatchput:

                        // mautic_api_categories_editbatchpatch
                        if ('/api/categories/batch/edit' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\CategoryBundle\\Controller\\Api\\CategoryApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_categories_editbatchpatch',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($requestMethod, ['PATCH'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['PATCH']);
                                }
                                goto not_mautic_api_categories_editbatchpatch;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_categories_editbatchpatch;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_categories_editbatchpatch', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_categories_editbatchpatch:

                    }

                    // mautic_api_categories_editput
                    if (preg_match('#^/api/categories/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_categories_editput']), array (  '_controller' => 'Mautic\\CategoryBundle\\Controller\\Api\\CategoryApiController::editEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PUT'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PUT']);
                            }
                            goto not_mautic_api_categories_editput;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_categories_editput;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_categories_editput', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_categories_editput:

                    // mautic_api_categories_editpatch
                    if (preg_match('#^/api/categories/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_categories_editpatch']), array (  '_controller' => 'Mautic\\CategoryBundle\\Controller\\Api\\CategoryApiController::editEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PATCH'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PATCH']);
                            }
                            goto not_mautic_api_categories_editpatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_categories_editpatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_categories_editpatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_categories_editpatch:

                    // mautic_api_categories_deletebatch
                    if ('/api/categories/batch/delete' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\CategoryBundle\\Controller\\Api\\CategoryApiController::deleteEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_categories_deletebatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_categories_deletebatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_categories_deletebatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_categories_deletebatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_categories_deletebatch:

                    // mautic_api_categories_delete
                    if (preg_match('#^/api/categories/(?P<id>\\d+)/delete$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_categories_delete']), array (  '_controller' => 'Mautic\\CategoryBundle\\Controller\\Api\\CategoryApiController::deleteEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_categories_delete;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_categories_delete;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_categories_delete', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_categories_delete:

                }

                elseif (0 === strpos($pathinfo, '/api/contacts')) {
                    // mautic_api_contacts_getall
                    if ('/api/contacts' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\LeadApiController::getEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_contacts_getall',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_contacts_getall;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_contacts_getall;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_contacts_getall', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_contacts_getall:

                    // mautic_api_contacts_getone
                    if (preg_match('#^/api/contacts/(?P<id>\\d+)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_contacts_getone']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\LeadApiController::getEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_contacts_getone;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_contacts_getone;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_contacts_getone', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_contacts_getone:

                    // mautic_api_contacts_new
                    if ('/api/contacts/new' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\LeadApiController::newEntityAction',  '_format' => 'json',  '_route' => 'mautic_api_contacts_new',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_contacts_new;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_contacts_new;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_contacts_new', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_contacts_new:

                    // mautic_api_contacts_newbatch
                    if ('/api/contacts/batch/new' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\LeadApiController::newEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_contacts_newbatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_contacts_newbatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_contacts_newbatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_contacts_newbatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_contacts_newbatch:

                    if (0 === strpos($pathinfo, '/api/contacts/batch/edit')) {
                        // mautic_api_contacts_editbatchput
                        if ('/api/contacts/batch/edit' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\LeadApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_contacts_editbatchput',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($requestMethod, ['PUT'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['PUT']);
                                }
                                goto not_mautic_api_contacts_editbatchput;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_contacts_editbatchput;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_contacts_editbatchput', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_contacts_editbatchput:

                        // mautic_api_contacts_editbatchpatch
                        if ('/api/contacts/batch/edit' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\LeadApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_contacts_editbatchpatch',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($requestMethod, ['PATCH'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['PATCH']);
                                }
                                goto not_mautic_api_contacts_editbatchpatch;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_contacts_editbatchpatch;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_contacts_editbatchpatch', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_contacts_editbatchpatch:

                    }

                    // mautic_api_contacts_editput
                    if (preg_match('#^/api/contacts/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_contacts_editput']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\LeadApiController::editEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PUT'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PUT']);
                            }
                            goto not_mautic_api_contacts_editput;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_contacts_editput;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_contacts_editput', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_contacts_editput:

                    // mautic_api_contacts_editpatch
                    if (preg_match('#^/api/contacts/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_contacts_editpatch']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\LeadApiController::editEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PATCH'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PATCH']);
                            }
                            goto not_mautic_api_contacts_editpatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_contacts_editpatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_contacts_editpatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_contacts_editpatch:

                    // mautic_api_contacts_deletebatch
                    if ('/api/contacts/batch/delete' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\LeadApiController::deleteEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_contacts_deletebatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_contacts_deletebatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_contacts_deletebatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_contacts_deletebatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_contacts_deletebatch:

                    // mautic_api_contacts_delete
                    if (preg_match('#^/api/contacts/(?P<id>\\d+)/delete$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_contacts_delete']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\LeadApiController::deleteEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_contacts_delete;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_contacts_delete;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_contacts_delete', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_contacts_delete:

                    // mautic_api_dncaddcontact
                    if (preg_match('#^/api/contacts/(?P<id>\\d+)/dnc/(?P<channel>[^/]++)/add$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_dncaddcontact']), array (  'channel' => 'email',  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\LeadApiController::addDncAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_dncaddcontact;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_dncaddcontact;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_dncaddcontact', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_dncaddcontact:

                    // mautic_api_dncremovecontact
                    if (preg_match('#^/api/contacts/(?P<id>\\d+)/dnc/(?P<channel>[^/]++)/remove$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_dncremovecontact']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\LeadApiController::removeDncAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_dncremovecontact;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_dncremovecontact;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_dncremovecontact', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_dncremovecontact:

                    // mautic_api_getcontactevents
                    if (preg_match('#^/api/contacts/(?P<id>\\d+)/activity$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_getcontactevents']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\LeadApiController::getActivityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_getcontactevents;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_getcontactevents;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_getcontactevents', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_getcontactevents:

                    // mautic_api_getcontactsevents
                    if ('/api/contacts/activity' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\LeadApiController::getAllActivityAction',  '_format' => 'json',  '_route' => 'mautic_api_getcontactsevents',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_getcontactsevents;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_getcontactsevents;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_getcontactsevents', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_getcontactsevents:

                    // mautic_api_getcontactnotes
                    if (preg_match('#^/api/contacts/(?P<id>\\d+)/notes$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_getcontactnotes']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\LeadApiController::getNotesAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_getcontactnotes;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_getcontactnotes;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_getcontactnotes', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_getcontactnotes:

                    // mautic_api_getcontactdevices
                    if (preg_match('#^/api/contacts/(?P<id>\\d+)/devices$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_getcontactdevices']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\LeadApiController::getDevicesAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_getcontactdevices;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_getcontactdevices;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_getcontactdevices', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_getcontactdevices:

                    // mautic_api_getcontactcampaigns
                    if (preg_match('#^/api/contacts/(?P<id>\\d+)/campaigns$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_getcontactcampaigns']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\LeadApiController::getCampaignsAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_getcontactcampaigns;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_getcontactcampaigns;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_getcontactcampaigns', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_getcontactcampaigns:

                    // mautic_api_getcontactssegments
                    if (preg_match('#^/api/contacts/(?P<id>\\d+)/segments$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_getcontactssegments']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\LeadApiController::getListsAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_getcontactssegments;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_getcontactssegments;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_getcontactssegments', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_getcontactssegments:

                    // mautic_api_getcontactscompanies
                    if (preg_match('#^/api/contacts/(?P<id>\\d+)/companies$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_getcontactscompanies']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\LeadApiController::getCompaniesAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_getcontactscompanies;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_getcontactscompanies;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_getcontactscompanies', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_getcontactscompanies:

                    // mautic_api_utmcreateevent
                    if (preg_match('#^/api/contacts/(?P<id>\\d+)/utm/add$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_utmcreateevent']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\LeadApiController::addUtmTagsAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_utmcreateevent;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_utmcreateevent;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_utmcreateevent', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_utmcreateevent:

                    // mautic_api_utmremoveevent
                    if (preg_match('#^/api/contacts/(?P<id>\\d+)/utm/(?P<utmid>[^/]++)/remove$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_utmremoveevent']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\LeadApiController::removeUtmTagsAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_utmremoveevent;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_utmremoveevent;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_utmremoveevent', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_utmremoveevent:

                    if (0 === strpos($pathinfo, '/api/contacts/list')) {
                        // mautic_api_getcontactowners
                        if ('/api/contacts/list/owners' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\LeadApiController::getOwnersAction',  '_format' => 'json',  '_route' => 'mautic_api_getcontactowners',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($canonicalMethod, ['GET'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['GET']);
                                }
                                goto not_mautic_api_getcontactowners;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_getcontactowners;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_getcontactowners', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_getcontactowners:

                        // mautic_api_getcontactfields
                        if ('/api/contacts/list/fields' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\LeadApiController::getFieldsAction',  '_format' => 'json',  '_route' => 'mautic_api_getcontactfields',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($canonicalMethod, ['GET'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['GET']);
                                }
                                goto not_mautic_api_getcontactfields;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_getcontactfields;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_getcontactfields', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_getcontactfields:

                        // mautic_api_getcontactsegments
                        if ('/api/contacts/list/segments' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\ListApiController::getListsAction',  '_format' => 'json',  '_route' => 'mautic_api_getcontactsegments',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($canonicalMethod, ['GET'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['GET']);
                                }
                                goto not_mautic_api_getcontactsegments;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_getcontactsegments;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_getcontactsegments', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_getcontactsegments:

                    }

                    // mautic_api_adjustcontactpoints
                    if (preg_match('#^/api/contacts/(?P<leadId>\\d+)/points/(?P<operator>[^/]++)/(?P<delta>[^/]++)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_adjustcontactpoints']), array (  '_controller' => 'Mautic\\PointBundle\\Controller\\Api\\PointApiController::adjustPointsAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_adjustcontactpoints;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_adjustcontactpoints;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_adjustcontactpoints', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_adjustcontactpoints:

                }

                elseif (0 === strpos($pathinfo, '/api/companies')) {
                    // mautic_api_companies_getall
                    if ('/api/companies' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\CompanyApiController::getEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_companies_getall',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_companies_getall;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_companies_getall;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_companies_getall', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_companies_getall:

                    // mautic_api_companies_getone
                    if (preg_match('#^/api/companies/(?P<id>\\d+)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_companies_getone']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\CompanyApiController::getEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_companies_getone;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_companies_getone;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_companies_getone', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_companies_getone:

                    // mautic_api_companies_new
                    if ('/api/companies/new' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\CompanyApiController::newEntityAction',  '_format' => 'json',  '_route' => 'mautic_api_companies_new',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_companies_new;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_companies_new;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_companies_new', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_companies_new:

                    // mautic_api_companies_newbatch
                    if ('/api/companies/batch/new' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\CompanyApiController::newEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_companies_newbatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_companies_newbatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_companies_newbatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_companies_newbatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_companies_newbatch:

                    if (0 === strpos($pathinfo, '/api/companies/batch/edit')) {
                        // mautic_api_companies_editbatchput
                        if ('/api/companies/batch/edit' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\CompanyApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_companies_editbatchput',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($requestMethod, ['PUT'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['PUT']);
                                }
                                goto not_mautic_api_companies_editbatchput;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_companies_editbatchput;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_companies_editbatchput', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_companies_editbatchput:

                        // mautic_api_companies_editbatchpatch
                        if ('/api/companies/batch/edit' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\CompanyApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_companies_editbatchpatch',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($requestMethod, ['PATCH'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['PATCH']);
                                }
                                goto not_mautic_api_companies_editbatchpatch;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_companies_editbatchpatch;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_companies_editbatchpatch', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_companies_editbatchpatch:

                    }

                    // mautic_api_companies_editput
                    if (preg_match('#^/api/companies/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_companies_editput']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\CompanyApiController::editEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PUT'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PUT']);
                            }
                            goto not_mautic_api_companies_editput;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_companies_editput;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_companies_editput', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_companies_editput:

                    // mautic_api_companies_editpatch
                    if (preg_match('#^/api/companies/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_companies_editpatch']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\CompanyApiController::editEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PATCH'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PATCH']);
                            }
                            goto not_mautic_api_companies_editpatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_companies_editpatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_companies_editpatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_companies_editpatch:

                    // mautic_api_companies_deletebatch
                    if ('/api/companies/batch/delete' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\CompanyApiController::deleteEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_companies_deletebatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_companies_deletebatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_companies_deletebatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_companies_deletebatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_companies_deletebatch:

                    // mautic_api_companies_delete
                    if (preg_match('#^/api/companies/(?P<id>\\d+)/delete$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_companies_delete']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\CompanyApiController::deleteEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_companies_delete;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_companies_delete;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_companies_delete', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_companies_delete:

                    // mautic_api_companyaddcontact
                    if (preg_match('#^/api/companies/(?P<companyId>\\d+)/contact/(?P<contactId>\\d+)/add$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_companyaddcontact']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\CompanyApiController::addContactAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_companyaddcontact;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_companyaddcontact;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_companyaddcontact', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_companyaddcontact:

                    // mautic_api_companyremovecontact
                    if (preg_match('#^/api/companies/(?P<companyId>\\d+)/contact/(?P<contactId>\\d+)/remove$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_companyremovecontact']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\CompanyApiController::removeContactAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_companyremovecontact;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_companyremovecontact;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_companyremovecontact', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_companyremovecontact:

                }

            }

            elseif (0 === strpos($pathinfo, '/api/messages')) {
                // mautic_api_messages_getall
                if ('/api/messages' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\ChannelBundle\\Controller\\Api\\MessageApiController::getEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_messages_getall',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($canonicalMethod, ['GET'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['GET']);
                        }
                        goto not_mautic_api_messages_getall;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_messages_getall;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_messages_getall', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_messages_getall:

                // mautic_api_messages_getone
                if (preg_match('#^/api/messages/(?P<id>\\d+)$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_messages_getone']), array (  '_controller' => 'Mautic\\ChannelBundle\\Controller\\Api\\MessageApiController::getEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($canonicalMethod, ['GET'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['GET']);
                        }
                        goto not_mautic_api_messages_getone;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_messages_getone;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_messages_getone', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_messages_getone:

                // mautic_api_messages_new
                if ('/api/messages/new' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\ChannelBundle\\Controller\\Api\\MessageApiController::newEntityAction',  '_format' => 'json',  '_route' => 'mautic_api_messages_new',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['POST'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['POST']);
                        }
                        goto not_mautic_api_messages_new;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_messages_new;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_messages_new', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_messages_new:

                // mautic_api_messages_newbatch
                if ('/api/messages/batch/new' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\ChannelBundle\\Controller\\Api\\MessageApiController::newEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_messages_newbatch',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['POST'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['POST']);
                        }
                        goto not_mautic_api_messages_newbatch;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_messages_newbatch;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_messages_newbatch', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_messages_newbatch:

                if (0 === strpos($pathinfo, '/api/messages/batch/edit')) {
                    // mautic_api_messages_editbatchput
                    if ('/api/messages/batch/edit' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\ChannelBundle\\Controller\\Api\\MessageApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_messages_editbatchput',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PUT'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PUT']);
                            }
                            goto not_mautic_api_messages_editbatchput;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_messages_editbatchput;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_messages_editbatchput', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_messages_editbatchput:

                    // mautic_api_messages_editbatchpatch
                    if ('/api/messages/batch/edit' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\ChannelBundle\\Controller\\Api\\MessageApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_messages_editbatchpatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PATCH'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PATCH']);
                            }
                            goto not_mautic_api_messages_editbatchpatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_messages_editbatchpatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_messages_editbatchpatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_messages_editbatchpatch:

                }

                // mautic_api_messages_editput
                if (preg_match('#^/api/messages/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_messages_editput']), array (  '_controller' => 'Mautic\\ChannelBundle\\Controller\\Api\\MessageApiController::editEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['PUT'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['PUT']);
                        }
                        goto not_mautic_api_messages_editput;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_messages_editput;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_messages_editput', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_messages_editput:

                // mautic_api_messages_editpatch
                if (preg_match('#^/api/messages/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_messages_editpatch']), array (  '_controller' => 'Mautic\\ChannelBundle\\Controller\\Api\\MessageApiController::editEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['PATCH'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['PATCH']);
                        }
                        goto not_mautic_api_messages_editpatch;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_messages_editpatch;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_messages_editpatch', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_messages_editpatch:

                // mautic_api_messages_deletebatch
                if ('/api/messages/batch/delete' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\ChannelBundle\\Controller\\Api\\MessageApiController::deleteEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_messages_deletebatch',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['DELETE'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['DELETE']);
                        }
                        goto not_mautic_api_messages_deletebatch;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_messages_deletebatch;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_messages_deletebatch', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_messages_deletebatch:

                // mautic_api_messages_delete
                if (preg_match('#^/api/messages/(?P<id>\\d+)/delete$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_messages_delete']), array (  '_controller' => 'Mautic\\ChannelBundle\\Controller\\Api\\MessageApiController::deleteEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['DELETE'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['DELETE']);
                        }
                        goto not_mautic_api_messages_delete;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_messages_delete;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_messages_delete', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_messages_delete:

            }

            elseif (0 === strpos($pathinfo, '/api/d')) {
                if (0 === strpos($pathinfo, '/api/data')) {
                    // mautic_widget_types
                    if ('/api/data' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\DashboardBundle\\Controller\\Api\\WidgetApiController::getTypesAction',  '_format' => 'json',  '_route' => 'mautic_widget_types',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_widget_types;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_widget_types;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_widget_types', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_widget_types:

                    // mautic_widget_data
                    if (preg_match('#^/api/data/(?P<type>[^/]++)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_widget_data']), array (  '_controller' => 'Mautic\\DashboardBundle\\Controller\\Api\\WidgetApiController::getDataAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_widget_data;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_widget_data;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_widget_data', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_widget_data:

                }

                elseif (0 === strpos($pathinfo, '/api/dynamiccontents')) {
                    // mautic_api_dynamicContents_getall
                    if ('/api/dynamiccontents' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\DynamicContentBundle\\Controller\\Api\\DynamicContentApiController::getEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_dynamicContents_getall',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_dynamicContents_getall;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_dynamicContents_getall;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_dynamicContents_getall', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_dynamicContents_getall:

                    // mautic_api_dynamicContents_getone
                    if (preg_match('#^/api/dynamiccontents/(?P<id>\\d+)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_dynamicContents_getone']), array (  '_controller' => 'Mautic\\DynamicContentBundle\\Controller\\Api\\DynamicContentApiController::getEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_dynamicContents_getone;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_dynamicContents_getone;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_dynamicContents_getone', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_dynamicContents_getone:

                    // mautic_api_dynamicContents_new
                    if ('/api/dynamiccontents/new' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\DynamicContentBundle\\Controller\\Api\\DynamicContentApiController::newEntityAction',  '_format' => 'json',  '_route' => 'mautic_api_dynamicContents_new',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_dynamicContents_new;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_dynamicContents_new;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_dynamicContents_new', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_dynamicContents_new:

                    // mautic_api_dynamicContents_newbatch
                    if ('/api/dynamiccontents/batch/new' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\DynamicContentBundle\\Controller\\Api\\DynamicContentApiController::newEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_dynamicContents_newbatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_dynamicContents_newbatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_dynamicContents_newbatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_dynamicContents_newbatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_dynamicContents_newbatch:

                    if (0 === strpos($pathinfo, '/api/dynamiccontents/batch/edit')) {
                        // mautic_api_dynamicContents_editbatchput
                        if ('/api/dynamiccontents/batch/edit' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\DynamicContentBundle\\Controller\\Api\\DynamicContentApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_dynamicContents_editbatchput',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($requestMethod, ['PUT'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['PUT']);
                                }
                                goto not_mautic_api_dynamicContents_editbatchput;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_dynamicContents_editbatchput;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_dynamicContents_editbatchput', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_dynamicContents_editbatchput:

                        // mautic_api_dynamicContents_editbatchpatch
                        if ('/api/dynamiccontents/batch/edit' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\DynamicContentBundle\\Controller\\Api\\DynamicContentApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_dynamicContents_editbatchpatch',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($requestMethod, ['PATCH'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['PATCH']);
                                }
                                goto not_mautic_api_dynamicContents_editbatchpatch;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_dynamicContents_editbatchpatch;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_dynamicContents_editbatchpatch', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_dynamicContents_editbatchpatch:

                    }

                    // mautic_api_dynamicContents_editput
                    if (preg_match('#^/api/dynamiccontents/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_dynamicContents_editput']), array (  '_controller' => 'Mautic\\DynamicContentBundle\\Controller\\Api\\DynamicContentApiController::editEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PUT'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PUT']);
                            }
                            goto not_mautic_api_dynamicContents_editput;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_dynamicContents_editput;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_dynamicContents_editput', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_dynamicContents_editput:

                    // mautic_api_dynamicContents_editpatch
                    if (preg_match('#^/api/dynamiccontents/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_dynamicContents_editpatch']), array (  '_controller' => 'Mautic\\DynamicContentBundle\\Controller\\Api\\DynamicContentApiController::editEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PATCH'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PATCH']);
                            }
                            goto not_mautic_api_dynamicContents_editpatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_dynamicContents_editpatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_dynamicContents_editpatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_dynamicContents_editpatch:

                    // mautic_api_dynamicContents_deletebatch
                    if ('/api/dynamiccontents/batch/delete' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\DynamicContentBundle\\Controller\\Api\\DynamicContentApiController::deleteEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_dynamicContents_deletebatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_dynamicContents_deletebatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_dynamicContents_deletebatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_dynamicContents_deletebatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_dynamicContents_deletebatch:

                    // mautic_api_dynamicContents_delete
                    if (preg_match('#^/api/dynamiccontents/(?P<id>\\d+)/delete$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_dynamicContents_delete']), array (  '_controller' => 'Mautic\\DynamicContentBundle\\Controller\\Api\\DynamicContentApiController::deleteEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_dynamicContents_delete;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_dynamicContents_delete;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_dynamicContents_delete', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_dynamicContents_delete:

                }

                elseif (0 === strpos($pathinfo, '/api/devices')) {
                    // mautic_api_devices_getall
                    if ('/api/devices' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\DeviceApiController::getEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_devices_getall',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_devices_getall;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_devices_getall;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_devices_getall', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_devices_getall:

                    // mautic_api_devices_getone
                    if (preg_match('#^/api/devices/(?P<id>\\d+)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_devices_getone']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\DeviceApiController::getEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_devices_getone;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_devices_getone;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_devices_getone', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_devices_getone:

                    // mautic_api_devices_new
                    if ('/api/devices/new' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\DeviceApiController::newEntityAction',  '_format' => 'json',  '_route' => 'mautic_api_devices_new',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_devices_new;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_devices_new;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_devices_new', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_devices_new:

                    // mautic_api_devices_newbatch
                    if ('/api/devices/batch/new' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\DeviceApiController::newEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_devices_newbatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_devices_newbatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_devices_newbatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_devices_newbatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_devices_newbatch:

                    if (0 === strpos($pathinfo, '/api/devices/batch/edit')) {
                        // mautic_api_devices_editbatchput
                        if ('/api/devices/batch/edit' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\DeviceApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_devices_editbatchput',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($requestMethod, ['PUT'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['PUT']);
                                }
                                goto not_mautic_api_devices_editbatchput;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_devices_editbatchput;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_devices_editbatchput', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_devices_editbatchput:

                        // mautic_api_devices_editbatchpatch
                        if ('/api/devices/batch/edit' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\DeviceApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_devices_editbatchpatch',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($requestMethod, ['PATCH'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['PATCH']);
                                }
                                goto not_mautic_api_devices_editbatchpatch;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_devices_editbatchpatch;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_devices_editbatchpatch', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_devices_editbatchpatch:

                    }

                    // mautic_api_devices_editput
                    if (preg_match('#^/api/devices/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_devices_editput']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\DeviceApiController::editEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PUT'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PUT']);
                            }
                            goto not_mautic_api_devices_editput;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_devices_editput;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_devices_editput', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_devices_editput:

                    // mautic_api_devices_editpatch
                    if (preg_match('#^/api/devices/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_devices_editpatch']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\DeviceApiController::editEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PATCH'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PATCH']);
                            }
                            goto not_mautic_api_devices_editpatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_devices_editpatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_devices_editpatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_devices_editpatch:

                    // mautic_api_devices_deletebatch
                    if ('/api/devices/batch/delete' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\DeviceApiController::deleteEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_devices_deletebatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_devices_deletebatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_devices_deletebatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_devices_deletebatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_devices_deletebatch:

                    // mautic_api_devices_delete
                    if (preg_match('#^/api/devices/(?P<id>\\d+)/delete$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_devices_delete']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\DeviceApiController::deleteEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_devices_delete;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_devices_delete;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_devices_delete', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_devices_delete:

                }

            }

            elseif (0 === strpos($pathinfo, '/api/emails')) {
                // mautic_api_emails_getall
                if ('/api/emails' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\EmailBundle\\Controller\\Api\\EmailApiController::getEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_emails_getall',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($canonicalMethod, ['GET'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['GET']);
                        }
                        goto not_mautic_api_emails_getall;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_emails_getall;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_emails_getall', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_emails_getall:

                // mautic_api_emails_getone
                if (preg_match('#^/api/emails/(?P<id>\\d+)$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_emails_getone']), array (  '_controller' => 'Mautic\\EmailBundle\\Controller\\Api\\EmailApiController::getEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($canonicalMethod, ['GET'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['GET']);
                        }
                        goto not_mautic_api_emails_getone;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_emails_getone;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_emails_getone', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_emails_getone:

                // mautic_api_emails_new
                if ('/api/emails/new' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\EmailBundle\\Controller\\Api\\EmailApiController::newEntityAction',  '_format' => 'json',  '_route' => 'mautic_api_emails_new',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['POST'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['POST']);
                        }
                        goto not_mautic_api_emails_new;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_emails_new;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_emails_new', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_emails_new:

                // mautic_api_emails_newbatch
                if ('/api/emails/batch/new' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\EmailBundle\\Controller\\Api\\EmailApiController::newEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_emails_newbatch',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['POST'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['POST']);
                        }
                        goto not_mautic_api_emails_newbatch;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_emails_newbatch;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_emails_newbatch', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_emails_newbatch:

                if (0 === strpos($pathinfo, '/api/emails/batch/edit')) {
                    // mautic_api_emails_editbatchput
                    if ('/api/emails/batch/edit' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\EmailBundle\\Controller\\Api\\EmailApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_emails_editbatchput',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PUT'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PUT']);
                            }
                            goto not_mautic_api_emails_editbatchput;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_emails_editbatchput;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_emails_editbatchput', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_emails_editbatchput:

                    // mautic_api_emails_editbatchpatch
                    if ('/api/emails/batch/edit' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\EmailBundle\\Controller\\Api\\EmailApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_emails_editbatchpatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PATCH'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PATCH']);
                            }
                            goto not_mautic_api_emails_editbatchpatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_emails_editbatchpatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_emails_editbatchpatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_emails_editbatchpatch:

                }

                // mautic_api_emails_editput
                if (preg_match('#^/api/emails/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_emails_editput']), array (  '_controller' => 'Mautic\\EmailBundle\\Controller\\Api\\EmailApiController::editEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['PUT'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['PUT']);
                        }
                        goto not_mautic_api_emails_editput;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_emails_editput;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_emails_editput', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_emails_editput:

                // mautic_api_emails_editpatch
                if (preg_match('#^/api/emails/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_emails_editpatch']), array (  '_controller' => 'Mautic\\EmailBundle\\Controller\\Api\\EmailApiController::editEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['PATCH'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['PATCH']);
                        }
                        goto not_mautic_api_emails_editpatch;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_emails_editpatch;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_emails_editpatch', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_emails_editpatch:

                // mautic_api_emails_deletebatch
                if ('/api/emails/batch/delete' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\EmailBundle\\Controller\\Api\\EmailApiController::deleteEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_emails_deletebatch',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['DELETE'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['DELETE']);
                        }
                        goto not_mautic_api_emails_deletebatch;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_emails_deletebatch;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_emails_deletebatch', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_emails_deletebatch:

                // mautic_api_emails_delete
                if (preg_match('#^/api/emails/(?P<id>\\d+)/delete$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_emails_delete']), array (  '_controller' => 'Mautic\\EmailBundle\\Controller\\Api\\EmailApiController::deleteEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['DELETE'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['DELETE']);
                        }
                        goto not_mautic_api_emails_delete;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_emails_delete;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_emails_delete', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_emails_delete:

                // mautic_api_sendemail
                if (preg_match('#^/api/emails/(?P<id>\\d+)/send$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_sendemail']), array (  '_controller' => 'Mautic\\EmailBundle\\Controller\\Api\\EmailApiController::sendAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['POST'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['POST']);
                        }
                        goto not_mautic_api_sendemail;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_sendemail;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_sendemail', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_sendemail:

                // mautic_api_sendcontactemail
                if (preg_match('#^/api/emails/(?P<id>\\d+)/contact/(?P<leadId>[^/]++)/send$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_sendcontactemail']), array (  '_controller' => 'Mautic\\EmailBundle\\Controller\\Api\\EmailApiController::sendLeadAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['POST'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['POST']);
                        }
                        goto not_mautic_api_sendcontactemail;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_sendcontactemail;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_sendcontactemail', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_sendcontactemail:

                // mautic_api_reply
                if (0 === strpos($pathinfo, '/api/emails/reply') && preg_match('#^/api/emails/reply/(?P<trackingHash>[^/]++)$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_reply']), array (  '_controller' => 'Mautic\\EmailBundle\\Controller\\Api\\EmailApiController::replyAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['POST'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['POST']);
                        }
                        goto not_mautic_api_reply;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_reply;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_reply', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_reply:

            }

            elseif (0 === strpos($pathinfo, '/api/notes')) {
                // mautic_api_notes_getall
                if ('/api/notes' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\NoteApiController::getEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_notes_getall',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($canonicalMethod, ['GET'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['GET']);
                        }
                        goto not_mautic_api_notes_getall;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_notes_getall;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_notes_getall', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_notes_getall:

                // mautic_api_notes_getone
                if (preg_match('#^/api/notes/(?P<id>\\d+)$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_notes_getone']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\NoteApiController::getEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($canonicalMethod, ['GET'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['GET']);
                        }
                        goto not_mautic_api_notes_getone;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_notes_getone;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_notes_getone', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_notes_getone:

                // mautic_api_notes_new
                if ('/api/notes/new' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\NoteApiController::newEntityAction',  '_format' => 'json',  '_route' => 'mautic_api_notes_new',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['POST'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['POST']);
                        }
                        goto not_mautic_api_notes_new;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_notes_new;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_notes_new', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_notes_new:

                // mautic_api_notes_newbatch
                if ('/api/notes/batch/new' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\NoteApiController::newEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_notes_newbatch',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['POST'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['POST']);
                        }
                        goto not_mautic_api_notes_newbatch;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_notes_newbatch;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_notes_newbatch', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_notes_newbatch:

                if (0 === strpos($pathinfo, '/api/notes/batch/edit')) {
                    // mautic_api_notes_editbatchput
                    if ('/api/notes/batch/edit' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\NoteApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_notes_editbatchput',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PUT'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PUT']);
                            }
                            goto not_mautic_api_notes_editbatchput;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_notes_editbatchput;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_notes_editbatchput', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_notes_editbatchput:

                    // mautic_api_notes_editbatchpatch
                    if ('/api/notes/batch/edit' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\NoteApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_notes_editbatchpatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PATCH'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PATCH']);
                            }
                            goto not_mautic_api_notes_editbatchpatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_notes_editbatchpatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_notes_editbatchpatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_notes_editbatchpatch:

                }

                // mautic_api_notes_editput
                if (preg_match('#^/api/notes/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_notes_editput']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\NoteApiController::editEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['PUT'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['PUT']);
                        }
                        goto not_mautic_api_notes_editput;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_notes_editput;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_notes_editput', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_notes_editput:

                // mautic_api_notes_editpatch
                if (preg_match('#^/api/notes/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_notes_editpatch']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\NoteApiController::editEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['PATCH'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['PATCH']);
                        }
                        goto not_mautic_api_notes_editpatch;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_notes_editpatch;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_notes_editpatch', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_notes_editpatch:

                // mautic_api_notes_deletebatch
                if ('/api/notes/batch/delete' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\NoteApiController::deleteEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_notes_deletebatch',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['DELETE'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['DELETE']);
                        }
                        goto not_mautic_api_notes_deletebatch;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_notes_deletebatch;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_notes_deletebatch', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_notes_deletebatch:

                // mautic_api_notes_delete
                if (preg_match('#^/api/notes/(?P<id>\\d+)/delete$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_notes_delete']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\Api\\NoteApiController::deleteEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['DELETE'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['DELETE']);
                        }
                        goto not_mautic_api_notes_delete;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_notes_delete;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_notes_delete', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_notes_delete:

            }

            elseif (0 === strpos($pathinfo, '/api/notifications')) {
                // mautic_api_notifications_getall
                if ('/api/notifications' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\NotificationBundle\\Controller\\Api\\NotificationApiController::getEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_notifications_getall',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($canonicalMethod, ['GET'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['GET']);
                        }
                        goto not_mautic_api_notifications_getall;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_notifications_getall;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_notifications_getall', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_notifications_getall:

                // mautic_api_notifications_getone
                if (preg_match('#^/api/notifications/(?P<id>\\d+)$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_notifications_getone']), array (  '_controller' => 'Mautic\\NotificationBundle\\Controller\\Api\\NotificationApiController::getEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($canonicalMethod, ['GET'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['GET']);
                        }
                        goto not_mautic_api_notifications_getone;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_notifications_getone;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_notifications_getone', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_notifications_getone:

                // mautic_api_notifications_new
                if ('/api/notifications/new' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\NotificationBundle\\Controller\\Api\\NotificationApiController::newEntityAction',  '_format' => 'json',  '_route' => 'mautic_api_notifications_new',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['POST'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['POST']);
                        }
                        goto not_mautic_api_notifications_new;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_notifications_new;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_notifications_new', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_notifications_new:

                // mautic_api_notifications_newbatch
                if ('/api/notifications/batch/new' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\NotificationBundle\\Controller\\Api\\NotificationApiController::newEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_notifications_newbatch',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['POST'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['POST']);
                        }
                        goto not_mautic_api_notifications_newbatch;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_notifications_newbatch;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_notifications_newbatch', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_notifications_newbatch:

                if (0 === strpos($pathinfo, '/api/notifications/batch/edit')) {
                    // mautic_api_notifications_editbatchput
                    if ('/api/notifications/batch/edit' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\NotificationBundle\\Controller\\Api\\NotificationApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_notifications_editbatchput',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PUT'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PUT']);
                            }
                            goto not_mautic_api_notifications_editbatchput;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_notifications_editbatchput;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_notifications_editbatchput', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_notifications_editbatchput:

                    // mautic_api_notifications_editbatchpatch
                    if ('/api/notifications/batch/edit' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\NotificationBundle\\Controller\\Api\\NotificationApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_notifications_editbatchpatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PATCH'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PATCH']);
                            }
                            goto not_mautic_api_notifications_editbatchpatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_notifications_editbatchpatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_notifications_editbatchpatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_notifications_editbatchpatch:

                }

                // mautic_api_notifications_editput
                if (preg_match('#^/api/notifications/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_notifications_editput']), array (  '_controller' => 'Mautic\\NotificationBundle\\Controller\\Api\\NotificationApiController::editEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['PUT'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['PUT']);
                        }
                        goto not_mautic_api_notifications_editput;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_notifications_editput;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_notifications_editput', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_notifications_editput:

                // mautic_api_notifications_editpatch
                if (preg_match('#^/api/notifications/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_notifications_editpatch']), array (  '_controller' => 'Mautic\\NotificationBundle\\Controller\\Api\\NotificationApiController::editEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['PATCH'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['PATCH']);
                        }
                        goto not_mautic_api_notifications_editpatch;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_notifications_editpatch;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_notifications_editpatch', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_notifications_editpatch:

                // mautic_api_notifications_deletebatch
                if ('/api/notifications/batch/delete' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\NotificationBundle\\Controller\\Api\\NotificationApiController::deleteEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_notifications_deletebatch',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['DELETE'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['DELETE']);
                        }
                        goto not_mautic_api_notifications_deletebatch;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_notifications_deletebatch;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_notifications_deletebatch', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_notifications_deletebatch:

                // mautic_api_notifications_delete
                if (preg_match('#^/api/notifications/(?P<id>\\d+)/delete$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_notifications_delete']), array (  '_controller' => 'Mautic\\NotificationBundle\\Controller\\Api\\NotificationApiController::deleteEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['DELETE'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['DELETE']);
                        }
                        goto not_mautic_api_notifications_delete;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_notifications_delete;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_notifications_delete', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_notifications_delete:

            }

            elseif (0 === strpos($pathinfo, '/api/pages')) {
                // mautic_api_pages_getall
                if ('/api/pages' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\PageBundle\\Controller\\Api\\PageApiController::getEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_pages_getall',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($canonicalMethod, ['GET'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['GET']);
                        }
                        goto not_mautic_api_pages_getall;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_pages_getall;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_pages_getall', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_pages_getall:

                // mautic_api_pages_getone
                if (preg_match('#^/api/pages/(?P<id>\\d+)$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_pages_getone']), array (  '_controller' => 'Mautic\\PageBundle\\Controller\\Api\\PageApiController::getEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($canonicalMethod, ['GET'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['GET']);
                        }
                        goto not_mautic_api_pages_getone;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_pages_getone;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_pages_getone', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_pages_getone:

                // mautic_api_pages_new
                if ('/api/pages/new' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\PageBundle\\Controller\\Api\\PageApiController::newEntityAction',  '_format' => 'json',  '_route' => 'mautic_api_pages_new',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['POST'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['POST']);
                        }
                        goto not_mautic_api_pages_new;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_pages_new;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_pages_new', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_pages_new:

                // mautic_api_pages_newbatch
                if ('/api/pages/batch/new' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\PageBundle\\Controller\\Api\\PageApiController::newEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_pages_newbatch',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['POST'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['POST']);
                        }
                        goto not_mautic_api_pages_newbatch;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_pages_newbatch;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_pages_newbatch', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_pages_newbatch:

                if (0 === strpos($pathinfo, '/api/pages/batch/edit')) {
                    // mautic_api_pages_editbatchput
                    if ('/api/pages/batch/edit' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\PageBundle\\Controller\\Api\\PageApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_pages_editbatchput',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PUT'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PUT']);
                            }
                            goto not_mautic_api_pages_editbatchput;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_pages_editbatchput;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_pages_editbatchput', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_pages_editbatchput:

                    // mautic_api_pages_editbatchpatch
                    if ('/api/pages/batch/edit' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\PageBundle\\Controller\\Api\\PageApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_pages_editbatchpatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PATCH'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PATCH']);
                            }
                            goto not_mautic_api_pages_editbatchpatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_pages_editbatchpatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_pages_editbatchpatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_pages_editbatchpatch:

                }

                // mautic_api_pages_editput
                if (preg_match('#^/api/pages/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_pages_editput']), array (  '_controller' => 'Mautic\\PageBundle\\Controller\\Api\\PageApiController::editEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['PUT'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['PUT']);
                        }
                        goto not_mautic_api_pages_editput;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_pages_editput;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_pages_editput', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_pages_editput:

                // mautic_api_pages_editpatch
                if (preg_match('#^/api/pages/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_pages_editpatch']), array (  '_controller' => 'Mautic\\PageBundle\\Controller\\Api\\PageApiController::editEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['PATCH'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['PATCH']);
                        }
                        goto not_mautic_api_pages_editpatch;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_pages_editpatch;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_pages_editpatch', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_pages_editpatch:

                // mautic_api_pages_deletebatch
                if ('/api/pages/batch/delete' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\PageBundle\\Controller\\Api\\PageApiController::deleteEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_pages_deletebatch',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['DELETE'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['DELETE']);
                        }
                        goto not_mautic_api_pages_deletebatch;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_pages_deletebatch;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_pages_deletebatch', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_pages_deletebatch:

                // mautic_api_pages_delete
                if (preg_match('#^/api/pages/(?P<id>\\d+)/delete$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_pages_delete']), array (  '_controller' => 'Mautic\\PageBundle\\Controller\\Api\\PageApiController::deleteEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['DELETE'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['DELETE']);
                        }
                        goto not_mautic_api_pages_delete;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_pages_delete;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_pages_delete', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_pages_delete:

            }

            elseif (0 === strpos($pathinfo, '/api/points')) {
                // mautic_api_points_getall
                if ('/api/points' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\PointBundle\\Controller\\Api\\PointApiController::getEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_points_getall',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($canonicalMethod, ['GET'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['GET']);
                        }
                        goto not_mautic_api_points_getall;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_points_getall;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_points_getall', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_points_getall:

                // mautic_api_points_getone
                if (preg_match('#^/api/points/(?P<id>\\d+)$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_points_getone']), array (  '_controller' => 'Mautic\\PointBundle\\Controller\\Api\\PointApiController::getEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($canonicalMethod, ['GET'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['GET']);
                        }
                        goto not_mautic_api_points_getone;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_points_getone;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_points_getone', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_points_getone:

                // mautic_api_points_new
                if ('/api/points/new' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\PointBundle\\Controller\\Api\\PointApiController::newEntityAction',  '_format' => 'json',  '_route' => 'mautic_api_points_new',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['POST'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['POST']);
                        }
                        goto not_mautic_api_points_new;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_points_new;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_points_new', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_points_new:

                // mautic_api_points_newbatch
                if ('/api/points/batch/new' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\PointBundle\\Controller\\Api\\PointApiController::newEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_points_newbatch',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['POST'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['POST']);
                        }
                        goto not_mautic_api_points_newbatch;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_points_newbatch;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_points_newbatch', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_points_newbatch:

                if (0 === strpos($pathinfo, '/api/points/batch/edit')) {
                    // mautic_api_points_editbatchput
                    if ('/api/points/batch/edit' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\PointBundle\\Controller\\Api\\PointApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_points_editbatchput',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PUT'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PUT']);
                            }
                            goto not_mautic_api_points_editbatchput;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_points_editbatchput;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_points_editbatchput', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_points_editbatchput:

                    // mautic_api_points_editbatchpatch
                    if ('/api/points/batch/edit' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\PointBundle\\Controller\\Api\\PointApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_points_editbatchpatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PATCH'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PATCH']);
                            }
                            goto not_mautic_api_points_editbatchpatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_points_editbatchpatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_points_editbatchpatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_points_editbatchpatch:

                }

                // mautic_api_points_editput
                if (preg_match('#^/api/points/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_points_editput']), array (  '_controller' => 'Mautic\\PointBundle\\Controller\\Api\\PointApiController::editEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['PUT'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['PUT']);
                        }
                        goto not_mautic_api_points_editput;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_points_editput;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_points_editput', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_points_editput:

                // mautic_api_points_editpatch
                if (preg_match('#^/api/points/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_points_editpatch']), array (  '_controller' => 'Mautic\\PointBundle\\Controller\\Api\\PointApiController::editEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['PATCH'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['PATCH']);
                        }
                        goto not_mautic_api_points_editpatch;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_points_editpatch;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_points_editpatch', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_points_editpatch:

                // mautic_api_points_deletebatch
                if ('/api/points/batch/delete' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\PointBundle\\Controller\\Api\\PointApiController::deleteEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_points_deletebatch',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['DELETE'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['DELETE']);
                        }
                        goto not_mautic_api_points_deletebatch;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_points_deletebatch;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_points_deletebatch', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_points_deletebatch:

                // mautic_api_points_delete
                if (preg_match('#^/api/points/(?P<id>\\d+)/delete$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_points_delete']), array (  '_controller' => 'Mautic\\PointBundle\\Controller\\Api\\PointApiController::deleteEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['DELETE'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['DELETE']);
                        }
                        goto not_mautic_api_points_delete;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_points_delete;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_points_delete', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_points_delete:

                // mautic_api_getpointactiontypes
                if ('/api/points/actions/types' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\PointBundle\\Controller\\Api\\PointApiController::getPointActionTypesAction',  '_format' => 'json',  '_route' => 'mautic_api_getpointactiontypes',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($canonicalMethod, ['GET'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['GET']);
                        }
                        goto not_mautic_api_getpointactiontypes;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_getpointactiontypes;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_getpointactiontypes', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_getpointactiontypes:

                if (0 === strpos($pathinfo, '/api/points/triggers')) {
                    // mautic_api_triggers_getall
                    if ('/api/points/triggers' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\PointBundle\\Controller\\Api\\TriggerApiController::getEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_triggers_getall',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_triggers_getall;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_triggers_getall;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_triggers_getall', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_triggers_getall:

                    // mautic_api_triggers_getone
                    if (preg_match('#^/api/points/triggers/(?P<id>\\d+)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_triggers_getone']), array (  '_controller' => 'Mautic\\PointBundle\\Controller\\Api\\TriggerApiController::getEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_triggers_getone;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_triggers_getone;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_triggers_getone', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_triggers_getone:

                    // mautic_api_triggers_new
                    if ('/api/points/triggers/new' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\PointBundle\\Controller\\Api\\TriggerApiController::newEntityAction',  '_format' => 'json',  '_route' => 'mautic_api_triggers_new',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_triggers_new;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_triggers_new;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_triggers_new', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_triggers_new:

                    // mautic_api_triggers_newbatch
                    if ('/api/points/triggers/batch/new' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\PointBundle\\Controller\\Api\\TriggerApiController::newEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_triggers_newbatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['POST'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['POST']);
                            }
                            goto not_mautic_api_triggers_newbatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_triggers_newbatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_triggers_newbatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_triggers_newbatch:

                    if (0 === strpos($pathinfo, '/api/points/triggers/batch/edit')) {
                        // mautic_api_triggers_editbatchput
                        if ('/api/points/triggers/batch/edit' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\PointBundle\\Controller\\Api\\TriggerApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_triggers_editbatchput',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($requestMethod, ['PUT'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['PUT']);
                                }
                                goto not_mautic_api_triggers_editbatchput;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_triggers_editbatchput;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_triggers_editbatchput', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_triggers_editbatchput:

                        // mautic_api_triggers_editbatchpatch
                        if ('/api/points/triggers/batch/edit' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\PointBundle\\Controller\\Api\\TriggerApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_triggers_editbatchpatch',);
                            $requiredSchemes = array (  'https' => 0,);
                            $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                            if (!in_array($requestMethod, ['PATCH'])) {
                                if ($hasRequiredScheme) {
                                    $allow = array_merge($allow, ['PATCH']);
                                }
                                goto not_mautic_api_triggers_editbatchpatch;
                            }
                            if (!$hasRequiredScheme) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_api_triggers_editbatchpatch;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_triggers_editbatchpatch', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_api_triggers_editbatchpatch:

                    }

                    // mautic_api_triggers_editput
                    if (preg_match('#^/api/points/triggers/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_triggers_editput']), array (  '_controller' => 'Mautic\\PointBundle\\Controller\\Api\\TriggerApiController::editEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PUT'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PUT']);
                            }
                            goto not_mautic_api_triggers_editput;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_triggers_editput;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_triggers_editput', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_triggers_editput:

                    // mautic_api_triggers_editpatch
                    if (preg_match('#^/api/points/triggers/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_triggers_editpatch']), array (  '_controller' => 'Mautic\\PointBundle\\Controller\\Api\\TriggerApiController::editEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PATCH'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PATCH']);
                            }
                            goto not_mautic_api_triggers_editpatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_triggers_editpatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_triggers_editpatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_triggers_editpatch:

                    // mautic_api_triggers_deletebatch
                    if ('/api/points/triggers/batch/delete' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\PointBundle\\Controller\\Api\\TriggerApiController::deleteEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_triggers_deletebatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_triggers_deletebatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_triggers_deletebatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_triggers_deletebatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_triggers_deletebatch:

                    // mautic_api_triggers_delete
                    if (preg_match('#^/api/points/triggers/(?P<id>\\d+)/delete$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_triggers_delete']), array (  '_controller' => 'Mautic\\PointBundle\\Controller\\Api\\TriggerApiController::deleteEntityAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_triggers_delete;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_triggers_delete;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_triggers_delete', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_triggers_delete:

                    // mautic_api_getpointtriggereventtypes
                    if ('/api/points/triggers/events/types' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\PointBundle\\Controller\\Api\\TriggerApiController::getPointTriggerEventTypesAction',  '_format' => 'json',  '_route' => 'mautic_api_getpointtriggereventtypes',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($canonicalMethod, ['GET'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['GET']);
                            }
                            goto not_mautic_api_getpointtriggereventtypes;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_getpointtriggereventtypes;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_getpointtriggereventtypes', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_getpointtriggereventtypes:

                    // mautic_api_pointtriggerdeleteevents
                    if (preg_match('#^/api/points/triggers/(?P<triggerId>\\d+)/events/delete$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_pointtriggerdeleteevents']), array (  '_controller' => 'Mautic\\PointBundle\\Controller\\Api\\TriggerApiController::deletePointTriggerEventsAction',  '_format' => 'json',));
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['DELETE'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['DELETE']);
                            }
                            goto not_mautic_api_pointtriggerdeleteevents;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_pointtriggerdeleteevents;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_pointtriggerdeleteevents', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_pointtriggerdeleteevents:

                }

            }

            elseif (0 === strpos($pathinfo, '/api/reports')) {
                // mautic_api_getreports
                if ('/api/reports' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\ReportBundle\\Controller\\Api\\ReportApiController::getEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_getreports',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($canonicalMethod, ['GET'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['GET']);
                        }
                        goto not_mautic_api_getreports;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_getreports;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_getreports', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_getreports:

                // mautic_api_getreport
                if (preg_match('#^/api/reports/(?P<id>\\d+)$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_getreport']), array (  '_controller' => 'Mautic\\ReportBundle\\Controller\\Api\\ReportApiController::getReportAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($canonicalMethod, ['GET'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['GET']);
                        }
                        goto not_mautic_api_getreport;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_getreport;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_getreport', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_getreport:

            }

            elseif (0 === strpos($pathinfo, '/api/roles')) {
                // mautic_api_roles_getall
                if ('/api/roles' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\UserBundle\\Controller\\Api\\RoleApiController::getEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_roles_getall',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($canonicalMethod, ['GET'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['GET']);
                        }
                        goto not_mautic_api_roles_getall;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_roles_getall;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_roles_getall', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_roles_getall:

                // mautic_api_roles_getone
                if (preg_match('#^/api/roles/(?P<id>\\d+)$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_roles_getone']), array (  '_controller' => 'Mautic\\UserBundle\\Controller\\Api\\RoleApiController::getEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($canonicalMethod, ['GET'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['GET']);
                        }
                        goto not_mautic_api_roles_getone;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_roles_getone;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_roles_getone', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_roles_getone:

                // mautic_api_roles_new
                if ('/api/roles/new' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\UserBundle\\Controller\\Api\\RoleApiController::newEntityAction',  '_format' => 'json',  '_route' => 'mautic_api_roles_new',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['POST'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['POST']);
                        }
                        goto not_mautic_api_roles_new;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_roles_new;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_roles_new', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_roles_new:

                // mautic_api_roles_newbatch
                if ('/api/roles/batch/new' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\UserBundle\\Controller\\Api\\RoleApiController::newEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_roles_newbatch',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['POST'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['POST']);
                        }
                        goto not_mautic_api_roles_newbatch;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_roles_newbatch;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_roles_newbatch', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_roles_newbatch:

                if (0 === strpos($pathinfo, '/api/roles/batch/edit')) {
                    // mautic_api_roles_editbatchput
                    if ('/api/roles/batch/edit' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\UserBundle\\Controller\\Api\\RoleApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_roles_editbatchput',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PUT'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PUT']);
                            }
                            goto not_mautic_api_roles_editbatchput;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_roles_editbatchput;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_roles_editbatchput', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_roles_editbatchput:

                    // mautic_api_roles_editbatchpatch
                    if ('/api/roles/batch/edit' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\UserBundle\\Controller\\Api\\RoleApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_roles_editbatchpatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PATCH'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PATCH']);
                            }
                            goto not_mautic_api_roles_editbatchpatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_roles_editbatchpatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_roles_editbatchpatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_roles_editbatchpatch:

                }

                // mautic_api_roles_editput
                if (preg_match('#^/api/roles/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_roles_editput']), array (  '_controller' => 'Mautic\\UserBundle\\Controller\\Api\\RoleApiController::editEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['PUT'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['PUT']);
                        }
                        goto not_mautic_api_roles_editput;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_roles_editput;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_roles_editput', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_roles_editput:

                // mautic_api_roles_editpatch
                if (preg_match('#^/api/roles/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_roles_editpatch']), array (  '_controller' => 'Mautic\\UserBundle\\Controller\\Api\\RoleApiController::editEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['PATCH'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['PATCH']);
                        }
                        goto not_mautic_api_roles_editpatch;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_roles_editpatch;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_roles_editpatch', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_roles_editpatch:

                // mautic_api_roles_deletebatch
                if ('/api/roles/batch/delete' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\UserBundle\\Controller\\Api\\RoleApiController::deleteEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_roles_deletebatch',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['DELETE'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['DELETE']);
                        }
                        goto not_mautic_api_roles_deletebatch;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_roles_deletebatch;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_roles_deletebatch', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_roles_deletebatch:

                // mautic_api_roles_delete
                if (preg_match('#^/api/roles/(?P<id>\\d+)/delete$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_roles_delete']), array (  '_controller' => 'Mautic\\UserBundle\\Controller\\Api\\RoleApiController::deleteEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['DELETE'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['DELETE']);
                        }
                        goto not_mautic_api_roles_delete;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_roles_delete;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_roles_delete', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_roles_delete:

            }

            elseif (0 === strpos($pathinfo, '/api/users')) {
                // mautic_api_users_getall
                if ('/api/users' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\UserBundle\\Controller\\Api\\UserApiController::getEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_users_getall',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($canonicalMethod, ['GET'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['GET']);
                        }
                        goto not_mautic_api_users_getall;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_users_getall;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_users_getall', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_users_getall:

                // mautic_api_users_getone
                if (preg_match('#^/api/users/(?P<id>\\d+)$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_users_getone']), array (  '_controller' => 'Mautic\\UserBundle\\Controller\\Api\\UserApiController::getEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($canonicalMethod, ['GET'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['GET']);
                        }
                        goto not_mautic_api_users_getone;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_users_getone;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_users_getone', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_users_getone:

                // mautic_api_users_new
                if ('/api/users/new' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\UserBundle\\Controller\\Api\\UserApiController::newEntityAction',  '_format' => 'json',  '_route' => 'mautic_api_users_new',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['POST'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['POST']);
                        }
                        goto not_mautic_api_users_new;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_users_new;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_users_new', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_users_new:

                // mautic_api_users_newbatch
                if ('/api/users/batch/new' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\UserBundle\\Controller\\Api\\UserApiController::newEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_users_newbatch',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['POST'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['POST']);
                        }
                        goto not_mautic_api_users_newbatch;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_users_newbatch;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_users_newbatch', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_users_newbatch:

                if (0 === strpos($pathinfo, '/api/users/batch/edit')) {
                    // mautic_api_users_editbatchput
                    if ('/api/users/batch/edit' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\UserBundle\\Controller\\Api\\UserApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_users_editbatchput',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PUT'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PUT']);
                            }
                            goto not_mautic_api_users_editbatchput;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_users_editbatchput;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_users_editbatchput', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_users_editbatchput:

                    // mautic_api_users_editbatchpatch
                    if ('/api/users/batch/edit' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\UserBundle\\Controller\\Api\\UserApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_users_editbatchpatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PATCH'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PATCH']);
                            }
                            goto not_mautic_api_users_editbatchpatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_users_editbatchpatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_users_editbatchpatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_users_editbatchpatch:

                }

                // mautic_api_users_editput
                if (preg_match('#^/api/users/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_users_editput']), array (  '_controller' => 'Mautic\\UserBundle\\Controller\\Api\\UserApiController::editEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['PUT'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['PUT']);
                        }
                        goto not_mautic_api_users_editput;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_users_editput;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_users_editput', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_users_editput:

                // mautic_api_users_editpatch
                if (preg_match('#^/api/users/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_users_editpatch']), array (  '_controller' => 'Mautic\\UserBundle\\Controller\\Api\\UserApiController::editEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['PATCH'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['PATCH']);
                        }
                        goto not_mautic_api_users_editpatch;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_users_editpatch;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_users_editpatch', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_users_editpatch:

                // mautic_api_users_deletebatch
                if ('/api/users/batch/delete' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\UserBundle\\Controller\\Api\\UserApiController::deleteEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_users_deletebatch',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['DELETE'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['DELETE']);
                        }
                        goto not_mautic_api_users_deletebatch;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_users_deletebatch;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_users_deletebatch', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_users_deletebatch:

                // mautic_api_users_delete
                if (preg_match('#^/api/users/(?P<id>\\d+)/delete$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_users_delete']), array (  '_controller' => 'Mautic\\UserBundle\\Controller\\Api\\UserApiController::deleteEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['DELETE'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['DELETE']);
                        }
                        goto not_mautic_api_users_delete;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_users_delete;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_users_delete', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_users_delete:

                // mautic_api_getself
                if ('/api/users/self' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\UserBundle\\Controller\\Api\\UserApiController::getSelfAction',  '_format' => 'json',  '_route' => 'mautic_api_getself',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($canonicalMethod, ['GET'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['GET']);
                        }
                        goto not_mautic_api_getself;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_getself;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_getself', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_getself:

                // mautic_api_checkpermission
                if (preg_match('#^/api/users/(?P<id>\\d+)/permissioncheck$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_checkpermission']), array (  '_controller' => 'Mautic\\UserBundle\\Controller\\Api\\UserApiController::isGrantedAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['POST'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['POST']);
                        }
                        goto not_mautic_api_checkpermission;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_checkpermission;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_checkpermission', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_checkpermission:

                // mautic_api_getuserroles
                if ('/api/users/list/roles' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\UserBundle\\Controller\\Api\\UserApiController::getRolesAction',  '_format' => 'json',  '_route' => 'mautic_api_getuserroles',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($canonicalMethod, ['GET'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['GET']);
                        }
                        goto not_mautic_api_getuserroles;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_getuserroles;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_getuserroles', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_getuserroles:

            }

            elseif (0 === strpos($pathinfo, '/api/hooks')) {
                // mautic_api_hooks_getall
                if ('/api/hooks' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\WebhookBundle\\Controller\\Api\\WebhookApiController::getEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_hooks_getall',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($canonicalMethod, ['GET'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['GET']);
                        }
                        goto not_mautic_api_hooks_getall;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_hooks_getall;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_hooks_getall', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_hooks_getall:

                // mautic_api_hooks_getone
                if (preg_match('#^/api/hooks/(?P<id>\\d+)$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_hooks_getone']), array (  '_controller' => 'Mautic\\WebhookBundle\\Controller\\Api\\WebhookApiController::getEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($canonicalMethod, ['GET'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['GET']);
                        }
                        goto not_mautic_api_hooks_getone;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_hooks_getone;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_hooks_getone', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_hooks_getone:

                // mautic_api_hooks_new
                if ('/api/hooks/new' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\WebhookBundle\\Controller\\Api\\WebhookApiController::newEntityAction',  '_format' => 'json',  '_route' => 'mautic_api_hooks_new',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['POST'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['POST']);
                        }
                        goto not_mautic_api_hooks_new;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_hooks_new;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_hooks_new', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_hooks_new:

                // mautic_api_hooks_newbatch
                if ('/api/hooks/batch/new' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\WebhookBundle\\Controller\\Api\\WebhookApiController::newEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_hooks_newbatch',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['POST'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['POST']);
                        }
                        goto not_mautic_api_hooks_newbatch;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_hooks_newbatch;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_hooks_newbatch', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_hooks_newbatch:

                if (0 === strpos($pathinfo, '/api/hooks/batch/edit')) {
                    // mautic_api_hooks_editbatchput
                    if ('/api/hooks/batch/edit' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\WebhookBundle\\Controller\\Api\\WebhookApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_hooks_editbatchput',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PUT'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PUT']);
                            }
                            goto not_mautic_api_hooks_editbatchput;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_hooks_editbatchput;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_hooks_editbatchput', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_hooks_editbatchput:

                    // mautic_api_hooks_editbatchpatch
                    if ('/api/hooks/batch/edit' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\WebhookBundle\\Controller\\Api\\WebhookApiController::editEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_hooks_editbatchpatch',);
                        $requiredSchemes = array (  'https' => 0,);
                        $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                        if (!in_array($requestMethod, ['PATCH'])) {
                            if ($hasRequiredScheme) {
                                $allow = array_merge($allow, ['PATCH']);
                            }
                            goto not_mautic_api_hooks_editbatchpatch;
                        }
                        if (!$hasRequiredScheme) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_api_hooks_editbatchpatch;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_hooks_editbatchpatch', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_api_hooks_editbatchpatch:

                }

                // mautic_api_hooks_editput
                if (preg_match('#^/api/hooks/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_hooks_editput']), array (  '_controller' => 'Mautic\\WebhookBundle\\Controller\\Api\\WebhookApiController::editEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['PUT'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['PUT']);
                        }
                        goto not_mautic_api_hooks_editput;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_hooks_editput;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_hooks_editput', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_hooks_editput:

                // mautic_api_hooks_editpatch
                if (preg_match('#^/api/hooks/(?P<id>\\d+)/edit$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_hooks_editpatch']), array (  '_controller' => 'Mautic\\WebhookBundle\\Controller\\Api\\WebhookApiController::editEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['PATCH'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['PATCH']);
                        }
                        goto not_mautic_api_hooks_editpatch;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_hooks_editpatch;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_hooks_editpatch', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_hooks_editpatch:

                // mautic_api_hooks_deletebatch
                if ('/api/hooks/batch/delete' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\WebhookBundle\\Controller\\Api\\WebhookApiController::deleteEntitiesAction',  '_format' => 'json',  '_route' => 'mautic_api_hooks_deletebatch',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['DELETE'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['DELETE']);
                        }
                        goto not_mautic_api_hooks_deletebatch;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_hooks_deletebatch;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_hooks_deletebatch', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_hooks_deletebatch:

                // mautic_api_hooks_delete
                if (preg_match('#^/api/hooks/(?P<id>\\d+)/delete$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_hooks_delete']), array (  '_controller' => 'Mautic\\WebhookBundle\\Controller\\Api\\WebhookApiController::deleteEntityAction',  '_format' => 'json',));
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['DELETE'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['DELETE']);
                        }
                        goto not_mautic_api_hooks_delete;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_hooks_delete;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_hooks_delete', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_hooks_delete:

                // mautic_api_webhookevents
                if ('/api/hooks/triggers' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\WebhookBundle\\Controller\\Api\\WebhookApiController::getTriggersAction',  '_format' => 'json',  '_route' => 'mautic_api_webhookevents',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($canonicalMethod, ['GET'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['GET']);
                        }
                        goto not_mautic_api_webhookevents;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_api_webhookevents;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_webhookevents', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_api_webhookevents:

            }

        }

        elseif (0 === strpos($pathinfo, '/dwc')) {
            // mautic_api_dynamicContent_index
            if ('/dwc' === $pathinfo) {
                $ret = array (  '_controller' => 'Mautic\\DynamicContentBundle\\Controller\\DynamicContentApiController::getEntitiesAction',  '_route' => 'mautic_api_dynamicContent_index',);
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_api_dynamicContent_index;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_dynamicContent_index', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_api_dynamicContent_index:

            // mautic_api_dynamicContent_action
            if (preg_match('#^/dwc/(?P<objectAlias>[^/]++)$#sD', $pathinfo, $matches)) {
                $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_api_dynamicContent_action']), array (  '_controller' => 'Mautic\\DynamicContentBundle\\Controller\\DynamicContentApiController::processAction',));
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_api_dynamicContent_action;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_api_dynamicContent_action', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_api_dynamicContent_action:

        }

        elseif (0 === strpos($pathinfo, '/p')) {
            if (0 === strpos($pathinfo, '/plugin')) {
                // mautic_plugin_tracker
                if (preg_match('#^/plugin/(?P<integration>.+)/tracking\\.gif$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_plugin_tracker']), array (  '_controller' => 'Mautic\\EmailBundle\\Controller\\PublicController::pluginTrackingGifAction',));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_plugin_tracker;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_plugin_tracker', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_plugin_tracker:

                if (0 === strpos($pathinfo, '/plugins/integrations/auth')) {
                    // mautic_integration_auth_user
                    if (0 === strpos($pathinfo, '/plugins/integrations/authuser') && preg_match('#^/plugins/integrations/authuser/(?P<integration>[^/]++)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_integration_auth_user']), array (  '_controller' => 'Mautic\\PluginBundle\\Controller\\AuthController::authUserAction',));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_integration_auth_user;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_integration_auth_user', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_integration_auth_user:

                    // mautic_integration_auth_callback
                    if (0 === strpos($pathinfo, '/plugins/integrations/authcallback') && preg_match('#^/plugins/integrations/authcallback/(?P<integration>[^/]++)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_integration_auth_callback']), array (  '_controller' => 'Mautic\\PluginBundle\\Controller\\AuthController::authCallbackAction',));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_integration_auth_callback;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_integration_auth_callback', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_integration_auth_callback:

                    // mautic_integration_auth_postauth
                    if (0 === strpos($pathinfo, '/plugins/integrations/authstatus') && preg_match('#^/plugins/integrations/authstatus/(?P<integration>[^/]++)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_integration_auth_postauth']), array (  '_controller' => 'Mautic\\PluginBundle\\Controller\\AuthController::authStatusAction',));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_integration_auth_postauth;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_integration_auth_postauth', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_integration_auth_postauth:

                }

                // mautic_integration_contacts
                if (preg_match('#^/plugin/(?P<integration>.+)/contact_data$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_integration_contacts']), array (  '_controller' => 'MauticPlugin\\MauticCrmBundle\\Controller\\PublicController::contactDataAction',));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_integration_contacts;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_integration_contacts', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_integration_contacts:

                // mautic_integration_companies
                if (preg_match('#^/plugin/(?P<integration>.+)/company_data$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_integration_companies']), array (  '_controller' => 'MauticPlugin\\MauticCrmBundle\\Controller\\PublicController::companyDataAction',));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_integration_companies;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_integration_companies', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_integration_companies:

                // mautic_integration.pipedrive.webhook
                if ('/plugin/pipedrive/webhook' === $pathinfo) {
                    $ret = array (  '_controller' => 'MauticPlugin\\MauticCrmBundle\\Controller\\PipedriveController::webhookAction',  '_route' => 'mautic_integration.pipedrive.webhook',);
                    $requiredSchemes = array (  'https' => 0,);
                    $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                    if (!in_array($requestMethod, ['POST'])) {
                        if ($hasRequiredScheme) {
                            $allow = array_merge($allow, ['POST']);
                        }
                        goto not_mautic_integrationpipedrivewebhook;
                    }
                    if (!$hasRequiredScheme) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_integrationpipedrivewebhook;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_integration.pipedrive.webhook', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_integrationpipedrivewebhook:

            }

            // mautic_page_preview
            if (0 === strpos($pathinfo, '/page/preview') && preg_match('#^/page/preview/(?P<id>[^/]++)$#sD', $pathinfo, $matches)) {
                $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_page_preview']), array (  '_controller' => 'Mautic\\PageBundle\\Controller\\PublicController::previewAction',));
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_page_preview;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_page_preview', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_page_preview:

            if (0 === strpos($pathinfo, '/passwordreset')) {
                // mautic_user_passwordreset
                if ('/passwordreset' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\UserBundle\\Controller\\PublicController::passwordResetAction',  '_route' => 'mautic_user_passwordreset',);
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_user_passwordreset;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_user_passwordreset', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_user_passwordreset:

                // mautic_user_passwordresetconfirm
                if ('/passwordresetconfirm' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\UserBundle\\Controller\\PublicController::passwordResetConfirmAction',  '_route' => 'mautic_user_passwordresetconfirm',);
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_user_passwordresetconfirm;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_user_passwordresetconfirm', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_user_passwordresetconfirm:

            }

        }

        elseif (0 === strpos($pathinfo, '/e')) {
            if (0 === strpos($pathinfo, '/email')) {
                // mautic_email_tracker
                if (preg_match('#^/email/(?P<idHash>[^/\\.]++)\\.gif$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_email_tracker']), array (  '_controller' => 'Mautic\\EmailBundle\\Controller\\PublicController::trackingImageAction',));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_email_tracker;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_email_tracker', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_email_tracker:

                // mautic_email_webview
                if (0 === strpos($pathinfo, '/email/view') && preg_match('#^/email/view/(?P<idHash>[^/]++)$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_email_webview']), array (  '_controller' => 'Mautic\\EmailBundle\\Controller\\PublicController::indexAction',));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_email_webview;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_email_webview', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_email_webview:

                // mautic_email_unsubscribe
                if (0 === strpos($pathinfo, '/email/unsubscribe') && preg_match('#^/email/unsubscribe/(?P<idHash>[^/]++)$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_email_unsubscribe']), array (  '_controller' => 'Mautic\\EmailBundle\\Controller\\PublicController::unsubscribeAction',));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_email_unsubscribe;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_email_unsubscribe', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_email_unsubscribe:

                // mautic_email_resubscribe
                if (0 === strpos($pathinfo, '/email/resubscribe') && preg_match('#^/email/resubscribe/(?P<idHash>[^/]++)$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_email_resubscribe']), array (  '_controller' => 'Mautic\\EmailBundle\\Controller\\PublicController::resubscribeAction',));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_email_resubscribe;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_email_resubscribe', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_email_resubscribe:

                // mautic_email_preview
                if (0 === strpos($pathinfo, '/email/preview') && preg_match('#^/email/preview(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_email_preview']), array (  '_controller' => 'Mautic\\EmailBundle\\Controller\\PublicController::previewAction',  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_email_preview;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_email_preview', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_email_preview:

            }

            // ef_connect
            if (0 === strpos($pathinfo, '/efconnect') && preg_match('#^/efconnect(?:/(?P<instance>[^/]++)(?:/(?P<homeFolder>[^/]++))?)?$#sD', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, ['_route' => 'ef_connect']), array (  '_controller' => 'FM\\ElfinderBundle\\Controller\\ElFinderController::loadAction',  'instance' => 'default',  'homeFolder' => '',));
            }

            // elfinder
            if (0 === strpos($pathinfo, '/elfinder') && preg_match('#^/elfinder(?:/(?P<instance>[^/]++)(?:/(?P<homeFolder>[^/]++))?)?$#sD', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, ['_route' => 'elfinder']), array (  '_controller' => 'FM\\ElfinderBundle\\Controller\\ElFinderController::showAction',  'instance' => 'default',  'homeFolder' => '',));
            }

        }

        elseif (0 === strpos($pathinfo, '/m')) {
            // mautic_mailer_transport_callback
            if (0 === strpos($pathinfo, '/mailer') && preg_match('#^/mailer/(?P<transport>[^/]++)/callback$#sD', $pathinfo, $matches)) {
                $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_mailer_transport_callback']), array (  '_controller' => 'Mautic\\EmailBundle\\Controller\\PublicController::mailerCallbackAction',));
                $requiredSchemes = array (  'https' => 0,);
                $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                if (!in_array($canonicalMethod, ['GET', 'POST'])) {
                    if ($hasRequiredScheme) {
                        $allow = array_merge($allow, ['GET', 'POST']);
                    }
                    goto not_mautic_mailer_transport_callback;
                }
                if (!$hasRequiredScheme) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_mailer_transport_callback;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_mailer_transport_callback', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_mailer_transport_callback:

            // mautic_onesignal_manifest
            if ('/manifest.json' === $pathinfo) {
                $ret = array (  '_controller' => 'Mautic\\NotificationBundle\\Controller\\JsController::manifestAction',  '_route' => 'mautic_onesignal_manifest',);
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_onesignal_manifest;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_onesignal_manifest', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_onesignal_manifest:

            // mautic_page_tracker
            if ('/mtracking.gif' === $pathinfo) {
                $ret = array (  '_controller' => 'Mautic\\PageBundle\\Controller\\PublicController::trackingImageAction',  '_route' => 'mautic_page_tracker',);
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_page_tracker;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_page_tracker', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_page_tracker:

            if (0 === strpos($pathinfo, '/mtc')) {
                // mautic_page_tracker_cors
                if ('/mtc/event' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\PageBundle\\Controller\\PublicController::trackingAction',  '_route' => 'mautic_page_tracker_cors',);
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_page_tracker_cors;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_page_tracker_cors', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_page_tracker_cors:

                // mautic_page_tracker_getcontact
                if ('/mtc' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\PageBundle\\Controller\\PublicController::getContactIdAction',  '_route' => 'mautic_page_tracker_getcontact',);
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_page_tracker_getcontact;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_page_tracker_getcontact', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_page_tracker_getcontact:

            }

        }

        elseif (0 === strpos($pathinfo, '/f')) {
            if (0 === strpos($pathinfo, '/form')) {
                // mautic_form_file_download
                if (0 === strpos($pathinfo, '/forms/results/file') && preg_match('#^/forms/results/file/(?P<submissionId>[^/]++)/(?P<field>[^/]++)$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_form_file_download']), array (  '_controller' => 'Mautic\\FormBundle\\Controller\\ResultController::downloadFileAction',));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_form_file_download;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_form_file_download', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_form_file_download:

                // mautic_form_postresults
                if ('/form/submit' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\FormBundle\\Controller\\PublicController::submitAction',  '_route' => 'mautic_form_postresults',);
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_form_postresults;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_form_postresults', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_form_postresults:

                // mautic_form_generateform
                if ('/form/generate.js' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\FormBundle\\Controller\\PublicController::generateAction',  '_route' => 'mautic_form_generateform',);
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_form_generateform;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_form_generateform', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_form_generateform:

                // mautic_form_postmessage
                if ('/form/message' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\FormBundle\\Controller\\PublicController::messageAction',  '_route' => 'mautic_form_postmessage',);
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_form_postmessage;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_form_postmessage', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_form_postmessage:

                // mautic_form_preview
                if (preg_match('#^/form(?:/(?P<id>[^/]++))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_form_preview']), array (  'id' => '0',  '_controller' => 'Mautic\\FormBundle\\Controller\\PublicController::previewAction',));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_form_preview;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_form_preview', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_form_preview:

                // mautic_form_embed
                if (0 === strpos($pathinfo, '/form/embed') && preg_match('#^/form/embed/(?P<id>[^/]++)$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_form_embed']), array (  '_controller' => 'Mautic\\FormBundle\\Controller\\PublicController::embedAction',));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_form_embed;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_form_embed', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_form_embed:

                // mautic_form_postresults_ajax
                if ('/form/submit/ajax' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\FormBundle\\Controller\\AjaxController::submitAction',  '_route' => 'mautic_form_postresults_ajax',);
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_form_postresults_ajax;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_form_postresults_ajax', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_form_postresults_ajax:

            }

            elseif (0 === strpos($pathinfo, '/focus')) {
                // mautic_focus_generate
                if (preg_match('#^/focus/(?P<id>[^/\\.]++)\\.js$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_focus_generate']), array (  '_controller' => 'MauticPlugin\\MauticFocusBundle\\Controller\\PublicController::generateAction',));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_focus_generate;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_focus_generate', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_focus_generate:

                // mautic_focus_pixel
                if (preg_match('#^/focus/(?P<id>[^/]++)/viewpixel\\.gif$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_focus_pixel']), array (  '_controller' => 'MauticPlugin\\MauticFocusBundle\\Controller\\PublicController::viewPixelAction',));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_focus_pixel;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_focus_pixel', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_focus_pixel:

            }

            // mautic_plugin_fullcontact_index
            if ('/fullcontact/callback' === $pathinfo) {
                $ret = array (  '_controller' => 'MauticPlugin\\MauticFullContactBundle\\Controller\\PublicController::callbackAction',  '_route' => 'mautic_plugin_fullcontact_index',);
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_plugin_fullcontact_index;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_plugin_fullcontact_index', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_plugin_fullcontact_index:

        }

        elseif (0 === strpos($pathinfo, '/installer')) {
            // mautic_installer_home
            if ('/installer' === $pathinfo) {
                $ret = array (  '_controller' => 'Mautic\\InstallBundle\\Controller\\InstallController::stepAction',  '_route' => 'mautic_installer_home',);
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_installer_home;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_installer_home', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_installer_home:

            // mautic_installer_remove_slash
            if ('/installer/' === $pathinfo) {
                $ret = array (  '_controller' => 'Mautic\\CoreBundle\\Controller\\CommonController::removeTrailingSlashAction',  '_route' => 'mautic_installer_remove_slash',);
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_installer_remove_slash;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_installer_remove_slash', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_installer_remove_slash:

            // mautic_installer_step
            if (0 === strpos($pathinfo, '/installer/step') && preg_match('#^/installer/step/(?P<index>[^/]++)$#sD', $pathinfo, $matches)) {
                $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_installer_step']), array (  '_controller' => 'Mautic\\InstallBundle\\Controller\\InstallController::stepAction',));
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_installer_step;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_installer_step', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_installer_step:

            // mautic_installer_final
            if ('/installer/final' === $pathinfo) {
                $ret = array (  '_controller' => 'Mautic\\InstallBundle\\Controller\\InstallController::finalAction',  '_route' => 'mautic_installer_final',);
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_installer_final;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_installer_final', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_installer_final:

            // mautic_installer_catchcall
            if (preg_match('#^/installer/(?P<noerror>(?).+)$#sD', $pathinfo, $matches)) {
                $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_installer_catchcall']), array (  '_controller' => 'Mautic\\InstallBundle\\Controller\\InstallController::stepAction',));
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_installer_catchcall;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_installer_catchcall', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_installer_catchcall:

        }

        // mautic_integration_public_callback
        if (0 === strpos($pathinfo, '/integration') && preg_match('#^/integration/(?P<integration>[^/]++)/callback$#sD', $pathinfo, $matches)) {
            $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_integration_public_callback']), array (  '_controller' => 'Mautic\\IntegrationsBundle\\Controller\\AuthController::callbackAction',));
            $requiredSchemes = array (  'https' => 0,);
            if (!isset($requiredSchemes[$context->getScheme()])) {
                if ('GET' !== $canonicalMethod) {
                    goto not_mautic_integration_public_callback;
                }

                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_integration_public_callback', key($requiredSchemes)));
            }

            return $ret;
        }
        not_mautic_integration_public_callback:

        if (0 === strpos($pathinfo, '/notification')) {
            // mautic_receive_notification
            if ('/notification/receive' === $pathinfo) {
                $ret = array (  '_controller' => 'Mautic\\NotificationBundle\\Controller\\Api\\NotificationApiController::receiveAction',  '_route' => 'mautic_receive_notification',);
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_receive_notification;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_receive_notification', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_receive_notification:

            // mautic_subscribe_notification
            if ('/notification/subscribe' === $pathinfo) {
                $ret = array (  '_controller' => 'Mautic\\NotificationBundle\\Controller\\Api\\NotificationApiController::subscribeAction',  '_route' => 'mautic_subscribe_notification',);
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_subscribe_notification;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_subscribe_notification', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_subscribe_notification:

            // mautic_notification_popup
            if ('/notification' === $pathinfo) {
                $ret = array (  '_controller' => 'Mautic\\NotificationBundle\\Controller\\PopupController::indexAction',  '_route' => 'mautic_notification_popup',);
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_notification_popup;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_notification_popup', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_notification_popup:

            // mautic_app_notification
            if ('/notification/appcallback' === $pathinfo) {
                $ret = array (  '_controller' => 'Mautic\\NotificationBundle\\Controller\\AppCallbackController::indexAction',  '_route' => 'mautic_app_notification',);
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_app_notification;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_app_notification', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_app_notification:

        }

        // mautic_onesignal_worker
        if ('/OneSignalSDKWorker.js' === $pathinfo) {
            $ret = array (  '_controller' => 'Mautic\\NotificationBundle\\Controller\\JsController::workerAction',  '_route' => 'mautic_onesignal_worker',);
            $requiredSchemes = array (  'https' => 0,);
            if (!isset($requiredSchemes[$context->getScheme()])) {
                if ('GET' !== $canonicalMethod) {
                    goto not_mautic_onesignal_worker;
                }

                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_onesignal_worker', key($requiredSchemes)));
            }

            return $ret;
        }
        not_mautic_onesignal_worker:

        // mautic_onesignal_updater
        if ('/OneSignalSDKUpdaterWorker.js' === $pathinfo) {
            $ret = array (  '_controller' => 'Mautic\\NotificationBundle\\Controller\\JsController::updaterAction',  '_route' => 'mautic_onesignal_updater',);
            $requiredSchemes = array (  'https' => 0,);
            if (!isset($requiredSchemes[$context->getScheme()])) {
                if ('GET' !== $canonicalMethod) {
                    goto not_mautic_onesignal_updater;
                }

                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_onesignal_updater', key($requiredSchemes)));
            }

            return $ret;
        }
        not_mautic_onesignal_updater:

        if (0 === strpos($pathinfo, '/r')) {
            // mautic_url_redirect
            if (preg_match('#^/r/(?P<redirectId>[^/]++)$#sD', $pathinfo, $matches)) {
                $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_url_redirect']), array (  '_controller' => 'Mautic\\PageBundle\\Controller\\PublicController::redirectAction',));
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_url_redirect;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_url_redirect', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_url_redirect:

            // mautic_page_redirect
            if (0 === strpos($pathinfo, '/redirect') && preg_match('#^/redirect/(?P<redirectId>[^/]++)$#sD', $pathinfo, $matches)) {
                $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_page_redirect']), array (  '_controller' => 'Mautic\\PageBundle\\Controller\\PublicController::redirectAction',));
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_page_redirect;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_page_redirect', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_page_redirect:

        }

        // mautic_gated_video_hit
        if ('/video/hit' === $pathinfo) {
            $ret = array (  '_controller' => 'Mautic\\PageBundle\\Controller\\PublicController::hitVideoAction',  '_route' => 'mautic_gated_video_hit',);
            $requiredSchemes = array (  'https' => 0,);
            if (!isset($requiredSchemes[$context->getScheme()])) {
                if ('GET' !== $canonicalMethod) {
                    goto not_mautic_gated_video_hit;
                }

                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_gated_video_hit', key($requiredSchemes)));
            }

            return $ret;
        }
        not_mautic_gated_video_hit:

        if (0 === strpos($pathinfo, '/s')) {
            if (0 === strpos($pathinfo, '/sms')) {
                // mautic_sms_callback
                if (preg_match('#^/sms/(?P<transport>[^/]++)/callback$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_sms_callback']), array (  '_controller' => 'Mautic\\SmsBundle\\Controller\\ReplyController::callbackAction',));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_sms_callback;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_sms_callback', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_sms_callback:

                // mautic_receive_sms
                if ('/sms/receive' === $pathinfo) {
                    $ret = array (  'transport' => 'twilio',  '_controller' => 'Mautic\\SmsBundle\\Controller\\ReplyController::callbackAction',  '_route' => 'mautic_receive_sms',);
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_receive_sms;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_receive_sms', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_receive_sms:

            }

            // lightsaml_sp.metadata
            if ('/saml/metadata.xml' === $pathinfo) {
                $ret = array (  '_controller' => 'LightSaml\\SpBundle\\Controller\\DefaultController::metadataAction',  '_route' => 'lightsaml_sp.metadata',);
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_lightsaml_spmetadata;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'lightsaml_sp.metadata', key($requiredSchemes)));
                }

                return $ret;
            }
            not_lightsaml_spmetadata:

            // lightsaml_sp.discovery
            if ('/saml/discovery' === $pathinfo) {
                $ret = array (  '_controller' => 'LightSaml\\SpBundle\\Controller\\DefaultController::discoveryAction',  '_route' => 'lightsaml_sp.discovery',);
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_lightsaml_spdiscovery;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'lightsaml_sp.discovery', key($requiredSchemes)));
                }

                return $ret;
            }
            not_lightsaml_spdiscovery:

            // mautic_social_js_generate
            if (0 === strpos($pathinfo, '/social/generate') && preg_match('#^/social/generate/(?P<formName>[^/\\.]++)\\.js$#sD', $pathinfo, $matches)) {
                $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_social_js_generate']), array (  '_controller' => 'MauticPlugin\\MauticSocialBundle\\Controller\\JsController::generateAction',));
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_social_js_generate;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_social_js_generate', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_social_js_generate:

            if (0 === strpos($pathinfo, '/s/a')) {
                // mautic_core_ajax
                if ('/s/ajax' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\CoreBundle\\Controller\\AjaxController::delegateAjaxAction',  '_route' => 'mautic_core_ajax',);
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_core_ajax;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_core_ajax', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_core_ajax:

                // mautic_core_form_action
                if (0 === strpos($pathinfo, '/s/action') && preg_match('#^/s/action/(?P<objectAction>[^/]++)(?:/(?P<objectModel>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?)?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_core_form_action']), array (  'objectModel' => '',  '_controller' => 'Mautic\\CoreBundle\\Controller\\FormController::executeAction',  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_core_form_action;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_core_form_action', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_core_form_action:

                if (0 === strpos($pathinfo, '/s/assets')) {
                    // mautic_asset_index
                    if (preg_match('#^/s/assets(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_asset_index']), array (  '_controller' => 'Mautic\\AssetBundle\\Controller\\AssetController::indexAction',  'page' => 0,));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_asset_index;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_asset_index', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_asset_index:

                    // mautic_asset_remote
                    if ('/s/assets/remote' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\AssetBundle\\Controller\\AssetController::remoteAction',  '_route' => 'mautic_asset_remote',);
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_asset_remote;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_asset_remote', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_asset_remote:

                    // mautic_asset_action
                    if (preg_match('#^/s/assets/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_asset_action']), array (  '_controller' => 'Mautic\\AssetBundle\\Controller\\AssetController::executeAction',  'objectId' => 0,));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_asset_action;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_asset_action', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_asset_action:

                }

            }

            elseif (0 === strpos($pathinfo, '/s/update')) {
                // mautic_core_update
                if ('/s/update' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\CoreBundle\\Controller\\UpdateController::indexAction',  '_route' => 'mautic_core_update',);
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_core_update;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_core_update', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_core_update:

                // mautic_core_update_schema
                if ('/s/update/schema' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\CoreBundle\\Controller\\UpdateController::schemaAction',  '_route' => 'mautic_core_update_schema',);
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_core_update_schema;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_core_update_schema', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_core_update_schema:

            }

            // mautic_core_file_action
            if (0 === strpos($pathinfo, '/s/file') && preg_match('#^/s/file/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_core_file_action']), array (  '_controller' => 'Mautic\\CoreBundle\\Controller\\FileController::executeAction',  'objectId' => 0,));
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_core_file_action;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_core_file_action', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_core_file_action:

            if (0 === strpos($pathinfo, '/s/forms')) {
                // mautic_formaction_action
                if (0 === strpos($pathinfo, '/s/forms/action') && preg_match('#^/s/forms/action/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_formaction_action']), array (  '_controller' => 'Mautic\\FormBundle\\Controller\\ActionController::executeAction',  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_formaction_action;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_formaction_action', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_formaction_action:

                // mautic_formfield_action
                if (0 === strpos($pathinfo, '/s/forms/field') && preg_match('#^/s/forms/field/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_formfield_action']), array (  '_controller' => 'Mautic\\FormBundle\\Controller\\FieldController::executeAction',  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_formfield_action;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_formfield_action', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_formfield_action:

                // mautic_form_index
                if (preg_match('#^/s/forms(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_form_index']), array (  '_controller' => 'Mautic\\FormBundle\\Controller\\FormController::indexAction',  'page' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_form_index;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_form_index', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_form_index:

                if (0 === strpos($pathinfo, '/s/forms/results')) {
                    // mautic_form_results
                    if (preg_match('#^/s/forms/results(?:/(?P<objectId>[a-zA-Z0-9_-]+)(?:/(?P<page>\\d+))?)?$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_form_results']), array (  '_controller' => 'Mautic\\FormBundle\\Controller\\ResultController::indexAction',  'page' => 0,  'objectId' => 0,));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_form_results;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_form_results', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_form_results:

                    // mautic_form_export
                    if (preg_match('#^/s/forms/results/(?P<objectId>[a-zA-Z0-9_-]+)/export(?:/(?P<format>[^/]++))?$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_form_export']), array (  'format' => 'csv',  '_controller' => 'Mautic\\FormBundle\\Controller\\ResultController::exportAction',  'objectId' => 0,));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_form_export;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_form_export', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_form_export:

                    // mautic_form_results_action
                    if (preg_match('#^/s/forms/results/(?P<formId>[^/]++)/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_form_results_action']), array (  'objectId' => 0,  '_controller' => 'Mautic\\FormBundle\\Controller\\ResultController::executeAction',));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_form_results_action;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_form_results_action', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_form_results_action:

                }

                // mautic_form_action
                if (preg_match('#^/s/forms/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_form_action']), array (  '_controller' => 'Mautic\\FormBundle\\Controller\\FormController::executeAction',  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_form_action;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_form_action', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_form_action:

            }

            elseif (0 === strpos($pathinfo, '/s/themes')) {
                // mautic_themes_index
                if ('/s/themes' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\CoreBundle\\Controller\\ThemeController::indexAction',  '_route' => 'mautic_themes_index',);
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_themes_index;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_themes_index', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_themes_index:

                // mautic_themes_action
                if (preg_match('#^/s/themes/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_themes_action']), array (  '_controller' => 'Mautic\\CoreBundle\\Controller\\ThemeController::executeAction',  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_themes_action;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_themes_action', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_themes_action:

            }

            elseif (0 === strpos($pathinfo, '/s/c')) {
                if (0 === strpos($pathinfo, '/s/credentials')) {
                    // mautic_client_index
                    if (preg_match('#^/s/credentials(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_client_index']), array (  '_controller' => 'Mautic\\ApiBundle\\Controller\\ClientController::indexAction',  'page' => 0,));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_client_index;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_client_index', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_client_index:

                    // mautic_client_action
                    if (preg_match('#^/s/credentials/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_client_action']), array (  '_controller' => 'Mautic\\ApiBundle\\Controller\\ClientController::executeAction',  'objectId' => 0,));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_client_action;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_client_action', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_client_action:

                }

                elseif (0 === strpos($pathinfo, '/s/ca')) {
                    if (0 === strpos($pathinfo, '/s/calendar')) {
                        // mautic_calendar_index
                        if ('/s/calendar' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\CalendarBundle\\Controller\\DefaultController::indexAction',  '_route' => 'mautic_calendar_index',);
                            $requiredSchemes = array (  'https' => 0,);
                            if (!isset($requiredSchemes[$context->getScheme()])) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_calendar_index;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_calendar_index', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_calendar_index:

                        // mautic_calendar_action
                        if (preg_match('#^/s/calendar/(?P<objectAction>[^/]++)$#sD', $pathinfo, $matches)) {
                            $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_calendar_action']), array (  '_controller' => 'Mautic\\CalendarBundle\\Controller\\DefaultController::executeAction',));
                            $requiredSchemes = array (  'https' => 0,);
                            if (!isset($requiredSchemes[$context->getScheme()])) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_calendar_action;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_calendar_action', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_calendar_action:

                    }

                    elseif (0 === strpos($pathinfo, '/s/campaigns')) {
                        // mautic_campaignevent_action
                        if (0 === strpos($pathinfo, '/s/campaigns/events') && preg_match('#^/s/campaigns/events/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                            $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_campaignevent_action']), array (  '_controller' => 'Mautic\\CampaignBundle\\Controller\\EventController::executeAction',  'objectId' => 0,));
                            $requiredSchemes = array (  'https' => 0,);
                            if (!isset($requiredSchemes[$context->getScheme()])) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_campaignevent_action;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_campaignevent_action', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_campaignevent_action:

                        // mautic_campaignsource_action
                        if (0 === strpos($pathinfo, '/s/campaigns/sources') && preg_match('#^/s/campaigns/sources/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                            $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_campaignsource_action']), array (  '_controller' => 'Mautic\\CampaignBundle\\Controller\\SourceController::executeAction',  'objectId' => 0,));
                            $requiredSchemes = array (  'https' => 0,);
                            if (!isset($requiredSchemes[$context->getScheme()])) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_campaignsource_action;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_campaignsource_action', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_campaignsource_action:

                        // mautic_campaign_index
                        if (preg_match('#^/s/campaigns(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                            $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_campaign_index']), array (  '_controller' => 'Mautic\\CampaignBundle\\Controller\\CampaignController::indexAction',  'page' => 0,));
                            $requiredSchemes = array (  'https' => 0,);
                            if (!isset($requiredSchemes[$context->getScheme()])) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_campaign_index;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_campaign_index', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_campaign_index:

                        // mautic_campaign_action
                        if (preg_match('#^/s/campaigns/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                            $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_campaign_action']), array (  '_controller' => 'Mautic\\CampaignBundle\\Controller\\CampaignController::executeAction',  'objectId' => 0,));
                            $requiredSchemes = array (  'https' => 0,);
                            if (!isset($requiredSchemes[$context->getScheme()])) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_campaign_action;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_campaign_action', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_campaign_action:

                        // mautic_campaign_contacts
                        if (0 === strpos($pathinfo, '/s/campaigns/view') && preg_match('#^/s/campaigns/view/(?P<objectId>[a-zA-Z0-9_-]+)/contact(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                            $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_campaign_contacts']), array (  '_controller' => 'Mautic\\CampaignBundle\\Controller\\CampaignController::contactsAction',  'page' => 0,  'objectId' => 0,));
                            $requiredSchemes = array (  'https' => 0,);
                            if (!isset($requiredSchemes[$context->getScheme()])) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_campaign_contacts;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_campaign_contacts', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_campaign_contacts:

                    }

                    // mautic_campaign_preview
                    if (0 === strpos($pathinfo, '/s/campaign/preview') && preg_match('#^/s/campaign/preview(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_campaign_preview']), array (  '_controller' => 'Mautic\\EmailBundle\\Controller\\PublicController::previewAction',  'objectId' => 0,));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_campaign_preview;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_campaign_preview', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_campaign_preview:

                    if (0 === strpos($pathinfo, '/s/categories')) {
                        // mautic_category_batch_contact_set
                        if ('/s/categories/batch/contact/set' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\CategoryBundle\\Controller\\BatchContactController::execAction',  '_route' => 'mautic_category_batch_contact_set',);
                            $requiredSchemes = array (  'https' => 0,);
                            if (!isset($requiredSchemes[$context->getScheme()])) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_category_batch_contact_set;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_category_batch_contact_set', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_category_batch_contact_set:

                        // mautic_category_batch_contact_view
                        if ('/s/categories/batch/contact/view' === $pathinfo) {
                            $ret = array (  '_controller' => 'Mautic\\CategoryBundle\\Controller\\BatchContactController::indexAction',  '_route' => 'mautic_category_batch_contact_view',);
                            $requiredSchemes = array (  'https' => 0,);
                            if (!isset($requiredSchemes[$context->getScheme()])) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_category_batch_contact_view;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_category_batch_contact_view', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_category_batch_contact_view:

                        // mautic_category_index
                        if (preg_match('#^/s/categories(?:/(?P<bundle>[^/]++)(?:/(?P<page>\\d+))?)?$#sD', $pathinfo, $matches)) {
                            $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_category_index']), array (  'bundle' => 'category',  '_controller' => 'Mautic\\CategoryBundle\\Controller\\CategoryController::indexAction',  'page' => 0,));
                            $requiredSchemes = array (  'https' => 0,);
                            if (!isset($requiredSchemes[$context->getScheme()])) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_category_index;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_category_index', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_category_index:

                        // mautic_category_action
                        if (preg_match('#^/s/categories/(?P<bundle>[^/]++)/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                            $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_category_action']), array (  'bundle' => 'category',  '_controller' => 'Mautic\\CategoryBundle\\Controller\\CategoryController::executeCategoryAction',  'objectId' => 0,));
                            $requiredSchemes = array (  'https' => 0,);
                            if (!isset($requiredSchemes[$context->getScheme()])) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_category_action;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_category_action', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_category_action:

                    }

                }

                // mautic_channel_batch_contact_set
                if ('/s/channels/batch/contact/set' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\ChannelBundle\\Controller\\BatchContactController::setAction',  '_route' => 'mautic_channel_batch_contact_set',);
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_channel_batch_contact_set;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_channel_batch_contact_set', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_channel_batch_contact_set:

                // mautic_channel_batch_contact_view
                if ('/s/channels/batch/contact/view' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\ChannelBundle\\Controller\\BatchContactController::indexAction',  '_route' => 'mautic_channel_batch_contact_view',);
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_channel_batch_contact_view;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_channel_batch_contact_view', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_channel_batch_contact_view:

                // mautic_config_action
                if (0 === strpos($pathinfo, '/s/config') && preg_match('#^/s/config/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_config_action']), array (  '_controller' => 'Mautic\\ConfigBundle\\Controller\\ConfigController::executeAction',  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_config_action;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_config_action', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_config_action:

                if (0 === strpos($pathinfo, '/s/contacts')) {
                    if (0 === strpos($pathinfo, '/s/contacts/fields')) {
                        // mautic_contactfield_index
                        if (preg_match('#^/s/contacts/fields(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                            $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_contactfield_index']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\FieldController::indexAction',  'page' => 0,));
                            $requiredSchemes = array (  'https' => 0,);
                            if (!isset($requiredSchemes[$context->getScheme()])) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_contactfield_index;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_contactfield_index', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_contactfield_index:

                        // mautic_contactfield_action
                        if (preg_match('#^/s/contacts/fields/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                            $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_contactfield_action']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\FieldController::executeAction',  'objectId' => 0,));
                            $requiredSchemes = array (  'https' => 0,);
                            if (!isset($requiredSchemes[$context->getScheme()])) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_contactfield_action;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_contactfield_action', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_contactfield_action:

                    }

                    // mautic_contact_index
                    if (preg_match('#^/s/contacts(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_contact_index']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\LeadController::indexAction',  'page' => 0,));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_contact_index;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_contact_index', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_contact_index:

                    if (0 === strpos($pathinfo, '/s/contacts/notes')) {
                        // mautic_contactnote_index
                        if (preg_match('#^/s/contacts/notes(?:/(?P<leadId>\\d+)(?:/(?P<page>\\d+))?)?$#sD', $pathinfo, $matches)) {
                            $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_contactnote_index']), array (  'leadId' => 0,  '_controller' => 'Mautic\\LeadBundle\\Controller\\NoteController::indexAction',  'page' => 0,));
                            $requiredSchemes = array (  'https' => 0,);
                            if (!isset($requiredSchemes[$context->getScheme()])) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_contactnote_index;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_contactnote_index', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_contactnote_index:

                        // mautic_contactnote_action
                        if (preg_match('#^/s/contacts/notes/(?P<leadId>\\d+)/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                            $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_contactnote_action']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\NoteController::executeNoteAction',  'objectId' => 0,));
                            $requiredSchemes = array (  'https' => 0,);
                            if (!isset($requiredSchemes[$context->getScheme()])) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_contactnote_action;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_contactnote_action', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_contactnote_action:

                    }

                    elseif (0 === strpos($pathinfo, '/s/contacts/timeline')) {
                        // mautic_contacttimeline_action
                        if (preg_match('#^/s/contacts/timeline/(?P<leadId>\\d+)(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                            $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_contacttimeline_action']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\TimelineController::indexAction',  'page' => 0,));
                            $requiredSchemes = array (  'https' => 0,);
                            if (!isset($requiredSchemes[$context->getScheme()])) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_contacttimeline_action;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_contacttimeline_action', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_contacttimeline_action:

                        // mautic_contact_timeline_export_action
                        if (0 === strpos($pathinfo, '/s/contacts/timeline/batchExport') && preg_match('#^/s/contacts/timeline/batchExport/(?P<leadId>\\d+)$#sD', $pathinfo, $matches)) {
                            $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_contact_timeline_export_action']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\TimelineController::batchExportAction',));
                            $requiredSchemes = array (  'https' => 0,);
                            if (!isset($requiredSchemes[$context->getScheme()])) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_contact_timeline_export_action;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_contact_timeline_export_action', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_contact_timeline_export_action:

                    }

                    elseif (0 === strpos($pathinfo, '/s/contacts/auditlog')) {
                        // mautic_contact_auditlog_action
                        if (preg_match('#^/s/contacts/auditlog/(?P<leadId>\\d+)(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                            $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_contact_auditlog_action']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\AuditlogController::indexAction',  'page' => 0,));
                            $requiredSchemes = array (  'https' => 0,);
                            if (!isset($requiredSchemes[$context->getScheme()])) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_contact_auditlog_action;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_contact_auditlog_action', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_contact_auditlog_action:

                        // mautic_contact_auditlog_export_action
                        if (0 === strpos($pathinfo, '/s/contacts/auditlog/batchExport') && preg_match('#^/s/contacts/auditlog/batchExport/(?P<leadId>\\d+)$#sD', $pathinfo, $matches)) {
                            $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_contact_auditlog_export_action']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\AuditlogController::batchExportAction',));
                            $requiredSchemes = array (  'https' => 0,);
                            if (!isset($requiredSchemes[$context->getScheme()])) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_contact_auditlog_export_action;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_contact_auditlog_export_action', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_contact_auditlog_export_action:

                    }

                    // mautic_contact_export_action
                    if (0 === strpos($pathinfo, '/s/contacts/contact/export') && preg_match('#^/s/contacts/contact/export/(?P<contactId>\\d+)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_contact_export_action']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\LeadController::contactExportAction',));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_contact_export_action;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_contact_export_action', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_contact_export_action:

                }

            }

            elseif (0 === strpos($pathinfo, '/s/messages')) {
                // mautic_message_index
                if (preg_match('#^/s/messages(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_message_index']), array (  '_controller' => 'Mautic\\ChannelBundle\\Controller\\MessageController::indexAction',  'page' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_message_index;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_message_index', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_message_index:

                // mautic_message_contacts
                if (0 === strpos($pathinfo, '/s/messages/contacts') && preg_match('#^/s/messages/contacts/(?P<objectId>[a-zA-Z0-9_-]+)/(?P<channel>[^/]++)(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_message_contacts']), array (  '_controller' => 'Mautic\\ChannelBundle\\Controller\\MessageController::contactsAction',  'page' => 0,  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_message_contacts;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_message_contacts', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_message_contacts:

                // mautic_message_action
                if (preg_match('#^/s/messages/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_message_action']), array (  '_controller' => 'Mautic\\ChannelBundle\\Controller\\MessageController::executeAction',  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_message_action;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_message_action', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_message_action:

            }

            // mautic_sysinfo_index
            if ('/s/sysinfo' === $pathinfo) {
                $ret = array (  '_controller' => 'Mautic\\ConfigBundle\\Controller\\SysinfoController::indexAction',  '_route' => 'mautic_sysinfo_index',);
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_sysinfo_index;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_sysinfo_index', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_sysinfo_index:

            if (0 === strpos($pathinfo, '/s/segments')) {
                // mautic_segment_batch_contact_set
                if ('/s/segments/batch/contact/set' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\BatchSegmentController::setAction',  '_route' => 'mautic_segment_batch_contact_set',);
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_segment_batch_contact_set;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_segment_batch_contact_set', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_segment_batch_contact_set:

                // mautic_segment_batch_contact_view
                if ('/s/segments/batch/contact/view' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\BatchSegmentController::indexAction',  '_route' => 'mautic_segment_batch_contact_view',);
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_segment_batch_contact_view;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_segment_batch_contact_view', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_segment_batch_contact_view:

                // mautic_segment_index
                if (preg_match('#^/s/segments(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_segment_index']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\ListController::indexAction',  'page' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_segment_index;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_segment_index', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_segment_index:

                // mautic_segment_action
                if (preg_match('#^/s/segments/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_segment_action']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\ListController::executeAction',  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_segment_action;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_segment_action', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_segment_action:

            }

            elseif (0 === strpos($pathinfo, '/s/dashboard')) {
                // mautic_dashboard_index
                if ('/s/dashboard' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\DashboardBundle\\Controller\\DashboardController::indexAction',  '_route' => 'mautic_dashboard_index',);
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_dashboard_index;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_dashboard_index', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_dashboard_index:

                // mautic_dashboard_widget
                if (0 === strpos($pathinfo, '/s/dashboard/widget') && preg_match('#^/s/dashboard/widget/(?P<widgetId>[^/]++)$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_dashboard_widget']), array (  '_controller' => 'Mautic\\DashboardBundle\\Controller\\DashboardController::widgetAction',));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_dashboard_widget;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_dashboard_widget', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_dashboard_widget:

                // mautic_dashboard_action
                if (preg_match('#^/s/dashboard/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_dashboard_action']), array (  '_controller' => 'Mautic\\DashboardBundle\\Controller\\DashboardController::executeAction',  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_dashboard_action;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_dashboard_action', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_dashboard_action:

            }

            elseif (0 === strpos($pathinfo, '/s/dwc')) {
                // mautic_dynamicContent_index
                if (preg_match('#^/s/dwc(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_dynamicContent_index']), array (  '_controller' => 'Mautic\\DynamicContentBundle\\Controller\\DynamicContentController::indexAction',  'page' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_dynamicContent_index;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_dynamicContent_index', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_dynamicContent_index:

                // mautic_dynamicContent_action
                if (preg_match('#^/s/dwc/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_dynamicContent_action']), array (  '_controller' => 'Mautic\\DynamicContentBundle\\Controller\\DynamicContentController::executeAction',  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_dynamicContent_action;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_dynamicContent_action', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_dynamicContent_action:

            }

            elseif (0 === strpos($pathinfo, '/s/emails')) {
                // mautic_email_index
                if (preg_match('#^/s/emails(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_email_index']), array (  '_controller' => 'Mautic\\EmailBundle\\Controller\\EmailController::indexAction',  'page' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_email_index;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_email_index', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_email_index:

                // mautic_email_graph_stats
                if (0 === strpos($pathinfo, '/s/emails-graph-stats') && preg_match('#^/s/emails\\-graph\\-stats/(?P<objectId>[a-zA-Z0-9_-]+)/(?P<isVariant>[^/]++)/(?P<dateFrom>[^/]++)/(?P<dateTo>[^/]++)$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_email_graph_stats']), array (  '_controller' => 'Mautic\\EmailBundle\\Controller\\EmailGraphStatsController::viewAction',  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_email_graph_stats;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_email_graph_stats', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_email_graph_stats:

                // mautic_email_action
                if (preg_match('#^/s/emails/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_email_action']), array (  '_controller' => 'Mautic\\EmailBundle\\Controller\\EmailController::executeAction',  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_email_action;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_email_action', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_email_action:

                // mautic_email_contacts
                if (0 === strpos($pathinfo, '/s/emails/view') && preg_match('#^/s/emails/view/(?P<objectId>[a-zA-Z0-9_-]+)/contact(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_email_contacts']), array (  '_controller' => 'Mautic\\EmailBundle\\Controller\\EmailController::contactsAction',  'page' => 0,  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_email_contacts;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_email_contacts', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_email_contacts:

            }

            elseif (0 === strpos($pathinfo, '/s/integration')) {
                // mautic_integration_config
                if (preg_match('#^/s/integration/(?P<integration>[^/]++)/config$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_integration_config']), array (  '_controller' => 'Mautic\\IntegrationsBundle\\Controller\\ConfigController::editAction',));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_integration_config;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_integration_config', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_integration_config:

                // mautic_integration_config_field_pagination
                if (preg_match('#^/s/integration/(?P<integration>[^/]++)/config/(?P<object>[^/]++)(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_integration_config_field_pagination']), array (  'page' => 1,  '_controller' => 'Mautic\\IntegrationsBundle\\Controller\\FieldPaginationController::paginateAction',));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_integration_config_field_pagination;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_integration_config_field_pagination', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_integration_config_field_pagination:

                // mautic_integration_config_field_update
                if (preg_match('#^/s/integration/(?P<integration>[^/]++)/config/(?P<object>[^/]++)/field/(?P<field>[^/]++)$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_integration_config_field_update']), array (  '_controller' => 'Mautic\\IntegrationsBundle\\Controller\\UpdateFieldController::updateAction',));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_integration_config_field_update;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_integration_config_field_update', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_integration_config_field_update:

            }

            elseif (0 === strpos($pathinfo, '/s/plugin')) {
                // mautic_plugin_timeline_index
                if (preg_match('#^/s/plugin/(?P<integration>.+)/timeline(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_plugin_timeline_index']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\TimelineController::pluginIndexAction',  'page' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_plugin_timeline_index;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_plugin_timeline_index', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_plugin_timeline_index:

                // mautic_plugin_timeline_view
                if (preg_match('#^/s/plugin/(?P<integration>.+)/timeline/view/(?P<leadId>\\d+)(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_plugin_timeline_view']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\TimelineController::pluginViewAction',  'page' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_plugin_timeline_view;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_plugin_timeline_view', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_plugin_timeline_view:

            }

            // mautic_import_index
            if (preg_match('#^/s/(?P<object>[^/]++)/import(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_import_index']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\ImportController::indexAction',  'page' => 0,));
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_import_index;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_import_index', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_import_index:

            // mautic_import_action
            if (preg_match('#^/s/(?P<object>[^/]++)/import/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_import_action']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\ImportController::executeAction',  'objectId' => 0,));
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_import_action;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_import_action', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_import_action:

            if (0 === strpos($pathinfo, '/s/co')) {
                // mautic_contact_action
                if (0 === strpos($pathinfo, '/s/contacts') && preg_match('#^/s/contacts/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_contact_action']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\LeadController::executeAction',  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_contact_action;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_contact_action', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_contact_action:

                if (0 === strpos($pathinfo, '/s/companies')) {
                    // mautic_company_index
                    if (preg_match('#^/s/companies(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_company_index']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\CompanyController::indexAction',  'page' => 0,));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_company_index;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_company_index', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_company_index:

                    // mautic_company_action
                    if (preg_match('#^/s/companies/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_company_action']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\CompanyController::executeAction',  'objectId' => 0,));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_company_action;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_company_action', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_company_action:

                    // mautic_company_export_action
                    if (0 === strpos($pathinfo, '/s/companies/company/export') && preg_match('#^/s/companies/company/export/(?P<companyId>\\d+)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_company_export_action']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\CompanyController::companyExportAction',));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_company_export_action;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_company_export_action', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_company_export_action:

                }

                // mautic_company_contacts_list
                if (0 === strpos($pathinfo, '/s/company') && preg_match('#^/s/company/(?P<objectId>\\d+)/contacts(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_company_contacts_list']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\CompanyController::contactsListAction',  'page' => 0,  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_company_contacts_list;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_company_contacts_list', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_company_contacts_list:

            }

            // mautic_plugin_clearbit_action
            if (0 === strpos($pathinfo, '/s/clearbit') && preg_match('#^/s/clearbit/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_plugin_clearbit_action']), array (  '_controller' => 'MauticPlugin\\MauticClearbitBundle\\Controller\\ClearbitController::executeAction',  'objectId' => 0,));
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_plugin_clearbit_action;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_plugin_clearbit_action', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_plugin_clearbit_action:

            if (0 === strpos($pathinfo, '/s/s')) {
                // mautic_segment_contacts
                if (0 === strpos($pathinfo, '/s/segment/view') && preg_match('#^/s/segment/view/(?P<objectId>[a-zA-Z0-9_-]+)/contact(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_segment_contacts']), array (  '_controller' => 'Mautic\\LeadBundle\\Controller\\ListController::contactsAction',  'page' => 0,  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_segment_contacts;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_segment_contacts', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_segment_contacts:

                if (0 === strpos($pathinfo, '/s/sms')) {
                    // mautic_sms_index
                    if (preg_match('#^/s/sms(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_sms_index']), array (  '_controller' => 'Mautic\\SmsBundle\\Controller\\SmsController::indexAction',  'page' => 0,));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_sms_index;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_sms_index', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_sms_index:

                    // mautic_sms_action
                    if (preg_match('#^/s/sms/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_sms_action']), array (  '_controller' => 'Mautic\\SmsBundle\\Controller\\SmsController::executeAction',  'objectId' => 0,));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_sms_action;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_sms_action', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_sms_action:

                    // mautic_sms_contacts
                    if (0 === strpos($pathinfo, '/s/sms/view') && preg_match('#^/s/sms/view/(?P<objectId>[a-zA-Z0-9_-]+)/contact(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_sms_contacts']), array (  '_controller' => 'Mautic\\SmsBundle\\Controller\\SmsController::contactsAction',  'page' => 0,  'objectId' => 0,));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_sms_contacts;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_sms_contacts', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_sms_contacts:

                }

                elseif (0 === strpos($pathinfo, '/s/stages')) {
                    // mautic_stage_index
                    if (preg_match('#^/s/stages(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_stage_index']), array (  '_controller' => 'Mautic\\StageBundle\\Controller\\StageController::indexAction',  'page' => 0,));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_stage_index;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_stage_index', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_stage_index:

                    // mautic_stage_action
                    if (preg_match('#^/s/stages/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_stage_action']), array (  '_controller' => 'Mautic\\StageBundle\\Controller\\StageController::executeAction',  'objectId' => 0,));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_stage_action;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_stage_action', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_stage_action:

                }

                elseif (0 === strpos($pathinfo, '/s/sso_login')) {
                    // mautic_sso_login
                    if (preg_match('#^/s/sso_login/(?P<integration>[^/]++)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_sso_login']), array (  '_controller' => 'Mautic\\UserBundle\\Controller\\SecurityController::ssoLoginAction',));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_sso_login;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_sso_login', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_sso_login:

                    // mautic_sso_login_check
                    if (0 === strpos($pathinfo, '/s/sso_login_check') && preg_match('#^/s/sso_login_check/(?P<integration>[^/]++)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_sso_login_check']), array (  '_controller' => 'Mautic\\UserBundle\\Controller\\SecurityController::ssoLoginCheckAction',));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_sso_login_check;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_sso_login_check', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_sso_login_check:

                }

                elseif (0 === strpos($pathinfo, '/s/saml/login')) {
                    // lightsaml_sp.login
                    if ('/s/saml/login' === $pathinfo) {
                        $ret = array (  '_controller' => 'LightSaml\\SpBundle\\Controller\\DefaultController::loginAction',  '_route' => 'lightsaml_sp.login',);
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_lightsaml_splogin;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'lightsaml_sp.login', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_lightsaml_splogin:

                    // lightsaml_sp.login_check
                    if ('/s/saml/login_check' === $pathinfo) {
                        $ret = ['_route' => 'lightsaml_sp.login_check'];
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_lightsaml_splogin_check;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'lightsaml_sp.login_check', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_lightsaml_splogin_check:

                }

            }

            elseif (0 === strpos($pathinfo, '/s/notifications')) {
                // mautic_notification_index
                if (preg_match('#^/s/notifications(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_notification_index']), array (  '_controller' => 'Mautic\\NotificationBundle\\Controller\\NotificationController::indexAction',  'page' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_notification_index;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_notification_index', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_notification_index:

                // mautic_notification_action
                if (preg_match('#^/s/notifications/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_notification_action']), array (  '_controller' => 'Mautic\\NotificationBundle\\Controller\\NotificationController::executeAction',  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_notification_action;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_notification_action', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_notification_action:

                // mautic_notification_contacts
                if (0 === strpos($pathinfo, '/s/notifications/view') && preg_match('#^/s/notifications/view/(?P<objectId>[a-zA-Z0-9_-]+)/contact(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_notification_contacts']), array (  '_controller' => 'Mautic\\NotificationBundle\\Controller\\NotificationController::contactsAction',  'page' => 0,  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_notification_contacts;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_notification_contacts', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_notification_contacts:

            }

            elseif (0 === strpos($pathinfo, '/s/mobile_notifications')) {
                // mautic_mobile_notification_index
                if (preg_match('#^/s/mobile_notifications(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_mobile_notification_index']), array (  '_controller' => 'Mautic\\NotificationBundle\\Controller\\MobileNotificationController::indexAction',  'page' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_mobile_notification_index;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_mobile_notification_index', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_mobile_notification_index:

                // mautic_mobile_notification_action
                if (preg_match('#^/s/mobile_notifications/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_mobile_notification_action']), array (  '_controller' => 'Mautic\\NotificationBundle\\Controller\\MobileNotificationController::executeAction',  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_mobile_notification_action;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_mobile_notification_action', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_mobile_notification_action:

                // mautic_mobile_notification_contacts
                if (0 === strpos($pathinfo, '/s/mobile_notifications/view') && preg_match('#^/s/mobile_notifications/view/(?P<objectId>[a-zA-Z0-9_-]+)/contact(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_mobile_notification_contacts']), array (  '_controller' => 'Mautic\\NotificationBundle\\Controller\\MobileNotificationController::contactsAction',  'page' => 0,  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_mobile_notification_contacts;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_mobile_notification_contacts', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_mobile_notification_contacts:

            }

            elseif (0 === strpos($pathinfo, '/s/monitoring')) {
                // mautic_social_index
                if (preg_match('#^/s/monitoring(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_social_index']), array (  '_controller' => 'MauticPlugin\\MauticSocialBundle\\Controller\\MonitoringController::indexAction',  'page' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_social_index;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_social_index', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_social_index:

                // mautic_social_action
                if (preg_match('#^/s/monitoring/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_social_action']), array (  '_controller' => 'MauticPlugin\\MauticSocialBundle\\Controller\\MonitoringController::executeAction',  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_social_action;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_social_action', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_social_action:

                // mautic_social_contacts
                if (0 === strpos($pathinfo, '/s/monitoring/view') && preg_match('#^/s/monitoring/view/(?P<objectId>[a-zA-Z0-9_-]+)/contacts(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_social_contacts']), array (  '_controller' => 'MauticPlugin\\MauticSocialBundle\\Controller\\MonitoringController::contactsAction',  'page' => 0,  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_social_contacts;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_social_contacts', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_social_contacts:

            }

            elseif (0 === strpos($pathinfo, '/s/p')) {
                if (0 === strpos($pathinfo, '/s/pages')) {
                    // mautic_page_index
                    if (preg_match('#^/s/pages(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_page_index']), array (  '_controller' => 'Mautic\\PageBundle\\Controller\\PageController::indexAction',  'page' => 0,));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_page_index;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_page_index', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_page_index:

                    // mautic_page_action
                    if (preg_match('#^/s/pages/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_page_action']), array (  '_controller' => 'Mautic\\PageBundle\\Controller\\PageController::executeAction',  'objectId' => 0,));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_page_action;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_page_action', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_page_action:

                }

                elseif (0 === strpos($pathinfo, '/s/plugins')) {
                    // mautic_integration_auth_callback_secure
                    if (0 === strpos($pathinfo, '/s/plugins/integrations/authcallback') && preg_match('#^/s/plugins/integrations/authcallback/(?P<integration>[^/]++)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_integration_auth_callback_secure']), array (  '_controller' => 'Mautic\\PluginBundle\\Controller\\AuthController::authCallbackAction',));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_integration_auth_callback_secure;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_integration_auth_callback_secure', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_integration_auth_callback_secure:

                    // mautic_integration_auth_postauth_secure
                    if (0 === strpos($pathinfo, '/s/plugins/integrations/authstatus') && preg_match('#^/s/plugins/integrations/authstatus/(?P<integration>[^/]++)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_integration_auth_postauth_secure']), array (  '_controller' => 'Mautic\\PluginBundle\\Controller\\AuthController::authStatusAction',));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_integration_auth_postauth_secure;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_integration_auth_postauth_secure', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_integration_auth_postauth_secure:

                    // mautic_plugin_index
                    if ('/s/plugins' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\PluginBundle\\Controller\\PluginController::indexAction',  '_route' => 'mautic_plugin_index',);
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_plugin_index;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_plugin_index', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_plugin_index:

                    // mautic_plugin_config
                    if (0 === strpos($pathinfo, '/s/plugins/config') && preg_match('#^/s/plugins/config/(?P<name>[^/]++)(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_plugin_config']), array (  '_controller' => 'Mautic\\PluginBundle\\Controller\\PluginController::configAction',  'page' => 0,));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_plugin_config;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_plugin_config', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_plugin_config:

                    // mautic_plugin_info
                    if (0 === strpos($pathinfo, '/s/plugins/info') && preg_match('#^/s/plugins/info/(?P<name>[^/]++)$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_plugin_info']), array (  '_controller' => 'Mautic\\PluginBundle\\Controller\\PluginController::infoAction',));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_plugin_info;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_plugin_info', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_plugin_info:

                    // mautic_plugin_reload
                    if ('/s/plugins/reload' === $pathinfo) {
                        $ret = array (  '_controller' => 'Mautic\\PluginBundle\\Controller\\PluginController::reloadAction',  '_route' => 'mautic_plugin_reload',);
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_plugin_reload;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_plugin_reload', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_plugin_reload:

                }

                elseif (0 === strpos($pathinfo, '/s/points')) {
                    if (0 === strpos($pathinfo, '/s/points/triggers')) {
                        // mautic_pointtriggerevent_action
                        if (0 === strpos($pathinfo, '/s/points/triggers/events') && preg_match('#^/s/points/triggers/events/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                            $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_pointtriggerevent_action']), array (  '_controller' => 'Mautic\\PointBundle\\Controller\\TriggerEventController::executeAction',  'objectId' => 0,));
                            $requiredSchemes = array (  'https' => 0,);
                            if (!isset($requiredSchemes[$context->getScheme()])) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_pointtriggerevent_action;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_pointtriggerevent_action', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_pointtriggerevent_action:

                        // mautic_pointtrigger_index
                        if (preg_match('#^/s/points/triggers(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                            $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_pointtrigger_index']), array (  '_controller' => 'Mautic\\PointBundle\\Controller\\TriggerController::indexAction',  'page' => 0,));
                            $requiredSchemes = array (  'https' => 0,);
                            if (!isset($requiredSchemes[$context->getScheme()])) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_pointtrigger_index;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_pointtrigger_index', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_pointtrigger_index:

                        // mautic_pointtrigger_action
                        if (preg_match('#^/s/points/triggers/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                            $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_pointtrigger_action']), array (  '_controller' => 'Mautic\\PointBundle\\Controller\\TriggerController::executeAction',  'objectId' => 0,));
                            $requiredSchemes = array (  'https' => 0,);
                            if (!isset($requiredSchemes[$context->getScheme()])) {
                                if ('GET' !== $canonicalMethod) {
                                    goto not_mautic_pointtrigger_action;
                                }

                                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_pointtrigger_action', key($requiredSchemes)));
                            }

                            return $ret;
                        }
                        not_mautic_pointtrigger_action:

                    }

                    // mautic_point_index
                    if (preg_match('#^/s/points(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_point_index']), array (  '_controller' => 'Mautic\\PointBundle\\Controller\\PointController::indexAction',  'page' => 0,));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_point_index;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_point_index', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_point_index:

                    // mautic_point_action
                    if (preg_match('#^/s/points/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_point_action']), array (  '_controller' => 'Mautic\\PointBundle\\Controller\\PointController::executeAction',  'objectId' => 0,));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_point_action;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_point_action', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_point_action:

                }

            }

            elseif (0 === strpos($pathinfo, '/s/reports')) {
                // mautic_report_index
                if (preg_match('#^/s/reports(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_report_index']), array (  '_controller' => 'Mautic\\ReportBundle\\Controller\\ReportController::indexAction',  'page' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_report_index;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_report_index', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_report_index:

                if (0 === strpos($pathinfo, '/s/reports/view')) {
                    // mautic_report_export
                    if (preg_match('#^/s/reports/view/(?P<objectId>[a-zA-Z0-9_-]+)/export(?:/(?P<format>[^/]++))?$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_report_export']), array (  'format' => 'csv',  '_controller' => 'Mautic\\ReportBundle\\Controller\\ReportController::exportAction',  'objectId' => 0,));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_report_export;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_report_export', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_report_export:

                    // mautic_report_view
                    if (preg_match('#^/s/reports/view(?:/(?P<objectId>[a-zA-Z0-9_-]+)(?:/(?P<reportPage>\\d+))?)?$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_report_view']), array (  'reportPage' => 1,  '_controller' => 'Mautic\\ReportBundle\\Controller\\ReportController::viewAction',  'objectId' => 0,));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_report_view;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_report_view', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_report_view:

                }

                // mautic_report_download
                if (0 === strpos($pathinfo, '/s/reports/download') && preg_match('#^/s/reports/download/(?P<reportId>[^/]++)(?:/(?P<format>[^/]++))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_report_download']), array (  'format' => 'csv',  '_controller' => 'Mautic\\ReportBundle\\Controller\\ReportController::downloadAction',));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_report_download;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_report_download', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_report_download:

                if (0 === strpos($pathinfo, '/s/reports/schedule')) {
                    // mautic_report_schedule_preview
                    if (0 === strpos($pathinfo, '/s/reports/schedule/preview') && preg_match('#^/s/reports/schedule/preview(?:/(?P<isScheduled>[^/]++)(?:/(?P<scheduleUnit>[^/]++)(?:/(?P<scheduleDay>[^/]++)(?:/(?P<scheduleMonthFrequency>[^/]++))?)?)?)?$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_report_schedule_preview']), array (  'isScheduled' => 0,  'scheduleUnit' => '',  'scheduleDay' => '',  'scheduleMonthFrequency' => '',  '_controller' => 'Mautic\\ReportBundle\\Controller\\ScheduleController::indexAction',));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_report_schedule_preview;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_report_schedule_preview', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_report_schedule_preview:

                    // mautic_report_schedule
                    if (preg_match('#^/s/reports/schedule/(?P<reportId>[^/]++)/now$#sD', $pathinfo, $matches)) {
                        $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_report_schedule']), array (  '_controller' => 'Mautic\\ReportBundle\\Controller\\ScheduleController::nowAction',));
                        $requiredSchemes = array (  'https' => 0,);
                        if (!isset($requiredSchemes[$context->getScheme()])) {
                            if ('GET' !== $canonicalMethod) {
                                goto not_mautic_report_schedule;
                            }

                            return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_report_schedule', key($requiredSchemes)));
                        }

                        return $ret;
                    }
                    not_mautic_report_schedule:

                }

                // mautic_report_action
                if (preg_match('#^/s/reports/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_report_action']), array (  '_controller' => 'Mautic\\ReportBundle\\Controller\\ReportController::executeAction',  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_report_action;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_report_action', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_report_action:

            }

            elseif (0 === strpos($pathinfo, '/s/roles')) {
                // mautic_role_index
                if (preg_match('#^/s/roles(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_role_index']), array (  '_controller' => 'Mautic\\UserBundle\\Controller\\RoleController::indexAction',  'page' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_role_index;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_role_index', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_role_index:

                // mautic_role_action
                if (preg_match('#^/s/roles/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_role_action']), array (  '_controller' => 'Mautic\\UserBundle\\Controller\\RoleController::executeAction',  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_role_action;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_role_action', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_role_action:

            }

            elseif (0 === strpos($pathinfo, '/s/login')) {
                // login
                if ('/s/login' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\UserBundle\\Controller\\SecurityController::loginAction',  '_route' => 'login',);
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_login;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'login', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_login:

                // mautic_user_logincheck
                if ('/s/login_check' === $pathinfo) {
                    $ret = array (  '_controller' => 'Mautic\\UserBundle\\Controller\\SecurityController::loginCheckAction',  '_route' => 'mautic_user_logincheck',);
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_user_logincheck;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_user_logincheck', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_user_logincheck:

            }

            // mautic_user_logout
            if ('/s/logout' === $pathinfo) {
                $ret = ['_route' => 'mautic_user_logout'];
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_user_logout;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_user_logout', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_user_logout:

            if (0 === strpos($pathinfo, '/s/users')) {
                // mautic_user_index
                if (preg_match('#^/s/users(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_user_index']), array (  '_controller' => 'Mautic\\UserBundle\\Controller\\UserController::indexAction',  'page' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_user_index;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_user_index', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_user_index:

                // mautic_user_action
                if (preg_match('#^/s/users/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_user_action']), array (  '_controller' => 'Mautic\\UserBundle\\Controller\\UserController::executeAction',  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_user_action;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_user_action', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_user_action:

            }

            // mautic_user_account
            if ('/s/account' === $pathinfo) {
                $ret = array (  '_controller' => 'Mautic\\UserBundle\\Controller\\ProfileController::indexAction',  '_route' => 'mautic_user_account',);
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_user_account;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_user_account', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_user_account:

            if (0 === strpos($pathinfo, '/s/webhooks')) {
                // mautic_webhook_index
                if (preg_match('#^/s/webhooks(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_webhook_index']), array (  '_controller' => 'Mautic\\WebhookBundle\\Controller\\WebhookController::indexAction',  'page' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_webhook_index;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_webhook_index', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_webhook_index:

                // mautic_webhook_action
                if (preg_match('#^/s/webhooks/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_webhook_action']), array (  '_controller' => 'Mautic\\WebhookBundle\\Controller\\WebhookController::executeAction',  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_webhook_action;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_webhook_action', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_webhook_action:

            }

            elseif (0 === strpos($pathinfo, '/s/grapesjsbuilder')) {
                // grapesjsbuilder_upload
                if ('/s/grapesjsbuilder/upload' === $pathinfo) {
                    $ret = array (  '_controller' => 'MauticPlugin\\GrapesJsBuilderBundle\\Controller\\FileManagerController::uploadAction',  '_route' => 'grapesjsbuilder_upload',);
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_grapesjsbuilder_upload;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'grapesjsbuilder_upload', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_grapesjsbuilder_upload:

                // grapesjsbuilder_delete
                if ('/s/grapesjsbuilder/delete' === $pathinfo) {
                    $ret = array (  '_controller' => 'MauticPlugin\\GrapesJsBuilderBundle\\Controller\\FileManagerController::deleteAction',  '_route' => 'grapesjsbuilder_delete',);
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_grapesjsbuilder_delete;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'grapesjsbuilder_delete', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_grapesjsbuilder_delete:

                // grapesjsbuilder_builder
                if (preg_match('#^/s/grapesjsbuilder/(?P<objectType>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'grapesjsbuilder_builder']), array (  '_controller' => 'MauticPlugin\\GrapesJsBuilderBundle\\Controller\\GrapesJsController::builderAction',  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_grapesjsbuilder_builder;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'grapesjsbuilder_builder', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_grapesjsbuilder_builder:

            }

            elseif (0 === strpos($pathinfo, '/s/focus')) {
                // mautic_focus_index
                if (preg_match('#^/s/focus(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_focus_index']), array (  '_controller' => 'MauticPlugin\\MauticFocusBundle\\Controller\\FocusController::indexAction',  'page' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_focus_index;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_focus_index', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_focus_index:

                // mautic_focus_action
                if (preg_match('#^/s/focus/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_focus_action']), array (  '_controller' => 'MauticPlugin\\MauticFocusBundle\\Controller\\FocusController::executeAction',  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_focus_action;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_focus_action', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_focus_action:

            }

            // mautic_plugin_fullcontact_action
            if (0 === strpos($pathinfo, '/s/fullcontact') && preg_match('#^/s/fullcontact/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_plugin_fullcontact_action']), array (  '_controller' => 'MauticPlugin\\MauticFullContactBundle\\Controller\\FullContactController::executeAction',  'objectId' => 0,));
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_plugin_fullcontact_action;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_plugin_fullcontact_action', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_plugin_fullcontact_action:

            if (0 === strpos($pathinfo, '/s/tweets')) {
                // mautic_tweet_index
                if (preg_match('#^/s/tweets(?:/(?P<page>\\d+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_tweet_index']), array (  '_controller' => 'MauticPlugin\\MauticSocialBundle\\Controller\\TweetController::indexAction',  'page' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_tweet_index;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_tweet_index', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_tweet_index:

                // mautic_tweet_action
                if (preg_match('#^/s/tweets/(?P<objectAction>[^/]++)(?:/(?P<objectId>[a-zA-Z0-9_-]+))?$#sD', $pathinfo, $matches)) {
                    $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_tweet_action']), array (  '_controller' => 'MauticPlugin\\MauticSocialBundle\\Controller\\TweetController::executeAction',  'objectId' => 0,));
                    $requiredSchemes = array (  'https' => 0,);
                    if (!isset($requiredSchemes[$context->getScheme()])) {
                        if ('GET' !== $canonicalMethod) {
                            goto not_mautic_tweet_action;
                        }

                        return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_tweet_action', key($requiredSchemes)));
                    }

                    return $ret;
                }
                not_mautic_tweet_action:

            }

            // _uploader_upload_asset
            if ('/s/_uploader/asset/upload' === $pathinfo) {
                $ret = array (  '_controller' => 'oneup_uploader.controller.mautic:upload',  '_format' => 'json',  '_route' => '_uploader_upload_asset',);
                $requiredSchemes = array (  'https' => 0,);
                $hasRequiredScheme = isset($requiredSchemes[$context->getScheme()]);
                if (!in_array($requestMethod, ['POST', 'PUT', 'PATCH'])) {
                    if ($hasRequiredScheme) {
                        $allow = array_merge($allow, ['POST', 'PUT', 'PATCH']);
                    }
                    goto not__uploader_upload_asset;
                }
                if (!$hasRequiredScheme) {
                    if ('GET' !== $canonicalMethod) {
                        goto not__uploader_upload_asset;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, '_uploader_upload_asset', key($requiredSchemes)));
                }

                return $ret;
            }
            not__uploader_upload_asset:

        }

        elseif (0 === strpos($pathinfo, '/c')) {
            // mautic_plugin_clearbit_index
            if ('/clearbit/callback' === $pathinfo) {
                $ret = array (  '_controller' => 'MauticPlugin\\MauticClearbitBundle\\Controller\\PublicController::callbackAction',  '_route' => 'mautic_plugin_clearbit_index',);
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_plugin_clearbit_index;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_plugin_clearbit_index', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_plugin_clearbit_index:

            // mautic_citrix_proxy
            if ('/citrix/proxy' === $pathinfo) {
                $ret = array (  '_controller' => 'MauticPlugin\\MauticCitrixBundle\\Controller\\PublicController::proxyAction',  '_route' => 'mautic_citrix_proxy',);
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_citrix_proxy;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_citrix_proxy', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_citrix_proxy:

            // mautic_citrix_sessionchanged
            if ('/citrix/sessionChanged' === $pathinfo) {
                $ret = array (  '_controller' => 'MauticPlugin\\MauticCitrixBundle\\Controller\\PublicController::sessionChangedAction',  '_route' => 'mautic_citrix_sessionchanged',);
                $requiredSchemes = array (  'https' => 0,);
                if (!isset($requiredSchemes[$context->getScheme()])) {
                    if ('GET' !== $canonicalMethod) {
                        goto not_mautic_citrix_sessionchanged;
                    }

                    return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_citrix_sessionchanged', key($requiredSchemes)));
                }

                return $ret;
            }
            not_mautic_citrix_sessionchanged:

        }

        // mautic_page_public
        if (preg_match('#^/(?P<slug>(?!(_(profiler|wdt)|css|images|js|favicon.ico|apps/bundles/|plugins/)).+)$#sD', $pathinfo, $matches)) {
            $ret = $this->mergeDefaults(array_replace($matches, ['_route' => 'mautic_page_public']), array (  '_controller' => 'Mautic\\PageBundle\\Controller\\PublicController::indexAction',));
            $requiredSchemes = array (  'https' => 0,);
            if (!isset($requiredSchemes[$context->getScheme()])) {
                if ('GET' !== $canonicalMethod) {
                    goto not_mautic_page_public;
                }

                return array_replace($ret, $this->redirect($rawPathinfo, 'mautic_page_public', key($requiredSchemes)));
            }

            return $ret;
        }
        not_mautic_page_public:

        if ('/' === $pathinfo && !$allow) {
            throw new Symfony\Component\Routing\Exception\NoConfigurationException();
        }

        throw 0 < count($allow) ? new MethodNotAllowedException(array_unique($allow)) : new ResourceNotFoundException();
    }
}
