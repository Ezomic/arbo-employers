import {
    queryParams,
    type RouteQueryOptions,
    type RouteDefinition,
    type RouteFormDefinition,
} from './../../../../../wayfinder';
/**
 * @see \App\Http\Controllers\Auth\SsoCallbackController::__invoke
 * @see app/Http/Controllers/Auth/SsoCallbackController.php:24
 * @route '/sso/callback'
 */
const SsoCallbackController = (
    options?: RouteQueryOptions,
): RouteDefinition<'get'> => ({
    url: SsoCallbackController.url(options),
    method: 'get',
});

SsoCallbackController.definition = {
    methods: ['get', 'head'],
    url: '/sso/callback',
} satisfies RouteDefinition<['get', 'head']>;

/**
 * @see \App\Http\Controllers\Auth\SsoCallbackController::__invoke
 * @see app/Http/Controllers/Auth/SsoCallbackController.php:24
 * @route '/sso/callback'
 */
SsoCallbackController.url = (options?: RouteQueryOptions) => {
    return SsoCallbackController.definition.url + queryParams(options);
};

/**
 * @see \App\Http\Controllers\Auth\SsoCallbackController::__invoke
 * @see app/Http/Controllers/Auth/SsoCallbackController.php:24
 * @route '/sso/callback'
 */
SsoCallbackController.get = (
    options?: RouteQueryOptions,
): RouteDefinition<'get'> => ({
    url: SsoCallbackController.url(options),
    method: 'get',
});

/**
 * @see \App\Http\Controllers\Auth\SsoCallbackController::__invoke
 * @see app/Http/Controllers/Auth/SsoCallbackController.php:24
 * @route '/sso/callback'
 */
SsoCallbackController.head = (
    options?: RouteQueryOptions,
): RouteDefinition<'head'> => ({
    url: SsoCallbackController.url(options),
    method: 'head',
});

/**
 * @see \App\Http\Controllers\Auth\SsoCallbackController::__invoke
 * @see app/Http/Controllers/Auth/SsoCallbackController.php:24
 * @route '/sso/callback'
 */
const SsoCallbackControllerForm = (
    options?: RouteQueryOptions,
): RouteFormDefinition<'get'> => ({
    action: SsoCallbackController.url(options),
    method: 'get',
});

/**
 * @see \App\Http\Controllers\Auth\SsoCallbackController::__invoke
 * @see app/Http/Controllers/Auth/SsoCallbackController.php:24
 * @route '/sso/callback'
 */
SsoCallbackControllerForm.get = (
    options?: RouteQueryOptions,
): RouteFormDefinition<'get'> => ({
    action: SsoCallbackController.url(options),
    method: 'get',
});

/**
 * @see \App\Http\Controllers\Auth\SsoCallbackController::__invoke
 * @see app/Http/Controllers/Auth/SsoCallbackController.php:24
 * @route '/sso/callback'
 */
SsoCallbackControllerForm.head = (
    options?: RouteQueryOptions,
): RouteFormDefinition<'get'> => ({
    action: SsoCallbackController.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        },
    }),
    method: 'get',
});

SsoCallbackController.form = SsoCallbackControllerForm;

export default SsoCallbackController;
