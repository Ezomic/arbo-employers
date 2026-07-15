import {
    queryParams,
    type RouteQueryOptions,
    type RouteDefinition,
    type RouteFormDefinition,
} from './../../../../wayfinder';
/**
 * @see \App\Http\Controllers\SelfServiceController::show
 * @see app/Http/Controllers/SelfServiceController.php:15
 * @route '/self-service'
 */
export const show = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
});

show.definition = {
    methods: ['get', 'head'],
    url: '/self-service',
} satisfies RouteDefinition<['get', 'head']>;

/**
 * @see \App\Http\Controllers\SelfServiceController::show
 * @see app/Http/Controllers/SelfServiceController.php:15
 * @route '/self-service'
 */
show.url = (options?: RouteQueryOptions) => {
    return show.definition.url + queryParams(options);
};

/**
 * @see \App\Http\Controllers\SelfServiceController::show
 * @see app/Http/Controllers/SelfServiceController.php:15
 * @route '/self-service'
 */
show.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
});

/**
 * @see \App\Http\Controllers\SelfServiceController::show
 * @see app/Http/Controllers/SelfServiceController.php:15
 * @route '/self-service'
 */
show.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(options),
    method: 'head',
});

/**
 * @see \App\Http\Controllers\SelfServiceController::show
 * @see app/Http/Controllers/SelfServiceController.php:15
 * @route '/self-service'
 */
const showForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(options),
    method: 'get',
});

/**
 * @see \App\Http\Controllers\SelfServiceController::show
 * @see app/Http/Controllers/SelfServiceController.php:15
 * @route '/self-service'
 */
showForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(options),
    method: 'get',
});

/**
 * @see \App\Http\Controllers\SelfServiceController::show
 * @see app/Http/Controllers/SelfServiceController.php:15
 * @route '/self-service'
 */
showForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        },
    }),
    method: 'get',
});

show.form = showForm;

/**
 * @see \App\Http\Controllers\SelfServiceController::gdprExport
 * @see app/Http/Controllers/SelfServiceController.php:42
 * @route '/self-service/gdpr-export'
 */
export const gdprExport = (
    options?: RouteQueryOptions,
): RouteDefinition<'get'> => ({
    url: gdprExport.url(options),
    method: 'get',
});

gdprExport.definition = {
    methods: ['get', 'head'],
    url: '/self-service/gdpr-export',
} satisfies RouteDefinition<['get', 'head']>;

/**
 * @see \App\Http\Controllers\SelfServiceController::gdprExport
 * @see app/Http/Controllers/SelfServiceController.php:42
 * @route '/self-service/gdpr-export'
 */
gdprExport.url = (options?: RouteQueryOptions) => {
    return gdprExport.definition.url + queryParams(options);
};

/**
 * @see \App\Http\Controllers\SelfServiceController::gdprExport
 * @see app/Http/Controllers/SelfServiceController.php:42
 * @route '/self-service/gdpr-export'
 */
gdprExport.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: gdprExport.url(options),
    method: 'get',
});

/**
 * @see \App\Http\Controllers\SelfServiceController::gdprExport
 * @see app/Http/Controllers/SelfServiceController.php:42
 * @route '/self-service/gdpr-export'
 */
gdprExport.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: gdprExport.url(options),
    method: 'head',
});

/**
 * @see \App\Http\Controllers\SelfServiceController::gdprExport
 * @see app/Http/Controllers/SelfServiceController.php:42
 * @route '/self-service/gdpr-export'
 */
const gdprExportForm = (
    options?: RouteQueryOptions,
): RouteFormDefinition<'get'> => ({
    action: gdprExport.url(options),
    method: 'get',
});

/**
 * @see \App\Http\Controllers\SelfServiceController::gdprExport
 * @see app/Http/Controllers/SelfServiceController.php:42
 * @route '/self-service/gdpr-export'
 */
gdprExportForm.get = (
    options?: RouteQueryOptions,
): RouteFormDefinition<'get'> => ({
    action: gdprExport.url(options),
    method: 'get',
});

/**
 * @see \App\Http\Controllers\SelfServiceController::gdprExport
 * @see app/Http/Controllers/SelfServiceController.php:42
 * @route '/self-service/gdpr-export'
 */
gdprExportForm.head = (
    options?: RouteQueryOptions,
): RouteFormDefinition<'get'> => ({
    action: gdprExport.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        },
    }),
    method: 'get',
});

gdprExport.form = gdprExportForm;

const SelfServiceController = { show, gdprExport };

export default SelfServiceController;
