import {
    queryParams,
    type RouteQueryOptions,
    type RouteDefinition,
    type RouteFormDefinition,
    applyUrlDefaults,
} from './../../../../wayfinder';
/**
 * @see \App\Http\Controllers\EmployeeImportController::store
 * @see app/Http/Controllers/EmployeeImportController.php:20
 * @route '/employer/employee-imports'
 */
export const store = (
    options?: RouteQueryOptions,
): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
});

store.definition = {
    methods: ['post'],
    url: '/employer/employee-imports',
} satisfies RouteDefinition<['post']>;

/**
 * @see \App\Http\Controllers\EmployeeImportController::store
 * @see app/Http/Controllers/EmployeeImportController.php:20
 * @route '/employer/employee-imports'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options);
};

/**
 * @see \App\Http\Controllers\EmployeeImportController::store
 * @see app/Http/Controllers/EmployeeImportController.php:20
 * @route '/employer/employee-imports'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
});

/**
 * @see \App\Http\Controllers\EmployeeImportController::store
 * @see app/Http/Controllers/EmployeeImportController.php:20
 * @route '/employer/employee-imports'
 */
const storeForm = (
    options?: RouteQueryOptions,
): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
});

/**
 * @see \App\Http\Controllers\EmployeeImportController::store
 * @see app/Http/Controllers/EmployeeImportController.php:20
 * @route '/employer/employee-imports'
 */
storeForm.post = (
    options?: RouteQueryOptions,
): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
});

store.form = storeForm;

/**
 * @see \App\Http\Controllers\EmployeeImportController::status
 * @see app/Http/Controllers/EmployeeImportController.php:37
 * @route '/employer/employee-imports/{import}'
 */
export const status = (
    args:
        | { import: string | number }
        | [importParam: string | number]
        | string
        | number,
    options?: RouteQueryOptions,
): RouteDefinition<'get'> => ({
    url: status.url(args, options),
    method: 'get',
});

status.definition = {
    methods: ['get', 'head'],
    url: '/employer/employee-imports/{import}',
} satisfies RouteDefinition<['get', 'head']>;

/**
 * @see \App\Http\Controllers\EmployeeImportController::status
 * @see app/Http/Controllers/EmployeeImportController.php:37
 * @route '/employer/employee-imports/{import}'
 */
status.url = (
    args:
        | { import: string | number }
        | [importParam: string | number]
        | string
        | number,
    options?: RouteQueryOptions,
) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { import: args };
    }

    if (Array.isArray(args)) {
        args = {
            import: args[0],
        };
    }

    args = applyUrlDefaults(args);

    const parsedArgs = {
        import: args.import,
    };

    return (
        status.definition.url
            .replace('{import}', parsedArgs.import.toString())
            .replace(/\/+$/, '') + queryParams(options)
    );
};

/**
 * @see \App\Http\Controllers\EmployeeImportController::status
 * @see app/Http/Controllers/EmployeeImportController.php:37
 * @route '/employer/employee-imports/{import}'
 */
status.get = (
    args:
        | { import: string | number }
        | [importParam: string | number]
        | string
        | number,
    options?: RouteQueryOptions,
): RouteDefinition<'get'> => ({
    url: status.url(args, options),
    method: 'get',
});

/**
 * @see \App\Http\Controllers\EmployeeImportController::status
 * @see app/Http/Controllers/EmployeeImportController.php:37
 * @route '/employer/employee-imports/{import}'
 */
status.head = (
    args:
        | { import: string | number }
        | [importParam: string | number]
        | string
        | number,
    options?: RouteQueryOptions,
): RouteDefinition<'head'> => ({
    url: status.url(args, options),
    method: 'head',
});

/**
 * @see \App\Http\Controllers\EmployeeImportController::status
 * @see app/Http/Controllers/EmployeeImportController.php:37
 * @route '/employer/employee-imports/{import}'
 */
const statusForm = (
    args:
        | { import: string | number }
        | [importParam: string | number]
        | string
        | number,
    options?: RouteQueryOptions,
): RouteFormDefinition<'get'> => ({
    action: status.url(args, options),
    method: 'get',
});

/**
 * @see \App\Http\Controllers\EmployeeImportController::status
 * @see app/Http/Controllers/EmployeeImportController.php:37
 * @route '/employer/employee-imports/{import}'
 */
statusForm.get = (
    args:
        | { import: string | number }
        | [importParam: string | number]
        | string
        | number,
    options?: RouteQueryOptions,
): RouteFormDefinition<'get'> => ({
    action: status.url(args, options),
    method: 'get',
});

/**
 * @see \App\Http\Controllers\EmployeeImportController::status
 * @see app/Http/Controllers/EmployeeImportController.php:37
 * @route '/employer/employee-imports/{import}'
 */
statusForm.head = (
    args:
        | { import: string | number }
        | [importParam: string | number]
        | string
        | number,
    options?: RouteQueryOptions,
): RouteFormDefinition<'get'> => ({
    action: status.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        },
    }),
    method: 'get',
});

status.form = statusForm;

const EmployeeImportController = { store, status };

export default EmployeeImportController;
