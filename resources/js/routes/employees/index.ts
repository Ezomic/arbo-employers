import {
    queryParams,
    type RouteQueryOptions,
    type RouteDefinition,
    type RouteFormDefinition,
    applyUrlDefaults,
} from './../../wayfinder';
/**
 * @see \App\Http\Controllers\EmployeeController::search
 * @see app/Http/Controllers/EmployeeController.php:20
 * @route '/employer/employees/search'
 */
export const search = (
    options?: RouteQueryOptions,
): RouteDefinition<'get'> => ({
    url: search.url(options),
    method: 'get',
});

search.definition = {
    methods: ['get', 'head'],
    url: '/employer/employees/search',
} satisfies RouteDefinition<['get', 'head']>;

/**
 * @see \App\Http\Controllers\EmployeeController::search
 * @see app/Http/Controllers/EmployeeController.php:20
 * @route '/employer/employees/search'
 */
search.url = (options?: RouteQueryOptions) => {
    return search.definition.url + queryParams(options);
};

/**
 * @see \App\Http\Controllers\EmployeeController::search
 * @see app/Http/Controllers/EmployeeController.php:20
 * @route '/employer/employees/search'
 */
search.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: search.url(options),
    method: 'get',
});

/**
 * @see \App\Http\Controllers\EmployeeController::search
 * @see app/Http/Controllers/EmployeeController.php:20
 * @route '/employer/employees/search'
 */
search.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: search.url(options),
    method: 'head',
});

/**
 * @see \App\Http\Controllers\EmployeeController::search
 * @see app/Http/Controllers/EmployeeController.php:20
 * @route '/employer/employees/search'
 */
const searchForm = (
    options?: RouteQueryOptions,
): RouteFormDefinition<'get'> => ({
    action: search.url(options),
    method: 'get',
});

/**
 * @see \App\Http\Controllers\EmployeeController::search
 * @see app/Http/Controllers/EmployeeController.php:20
 * @route '/employer/employees/search'
 */
searchForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: search.url(options),
    method: 'get',
});

/**
 * @see \App\Http\Controllers\EmployeeController::search
 * @see app/Http/Controllers/EmployeeController.php:20
 * @route '/employer/employees/search'
 */
searchForm.head = (
    options?: RouteQueryOptions,
): RouteFormDefinition<'get'> => ({
    action: search.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        },
    }),
    method: 'get',
});

search.form = searchForm;

/**
 * @see \App\Http\Controllers\EmployeeController::store
 * @see app/Http/Controllers/EmployeeController.php:81
 * @route '/employer/employees'
 */
export const store = (
    options?: RouteQueryOptions,
): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
});

store.definition = {
    methods: ['post'],
    url: '/employer/employees',
} satisfies RouteDefinition<['post']>;

/**
 * @see \App\Http\Controllers\EmployeeController::store
 * @see app/Http/Controllers/EmployeeController.php:81
 * @route '/employer/employees'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options);
};

/**
 * @see \App\Http\Controllers\EmployeeController::store
 * @see app/Http/Controllers/EmployeeController.php:81
 * @route '/employer/employees'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
});

/**
 * @see \App\Http\Controllers\EmployeeController::store
 * @see app/Http/Controllers/EmployeeController.php:81
 * @route '/employer/employees'
 */
const storeForm = (
    options?: RouteQueryOptions,
): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
});

/**
 * @see \App\Http\Controllers\EmployeeController::store
 * @see app/Http/Controllers/EmployeeController.php:81
 * @route '/employer/employees'
 */
storeForm.post = (
    options?: RouteQueryOptions,
): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
});

store.form = storeForm;

/**
 * @see \App\Http\Controllers\EmployeeController::edit
 * @see app/Http/Controllers/EmployeeController.php:116
 * @route '/employer/employees/{employee}/edit'
 */
export const edit = (
    args:
        | { employee: string | { id: string } }
        | [employee: string | { id: string }]
        | string
        | { id: string },
    options?: RouteQueryOptions,
): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
});

edit.definition = {
    methods: ['get', 'head'],
    url: '/employer/employees/{employee}/edit',
} satisfies RouteDefinition<['get', 'head']>;

/**
 * @see \App\Http\Controllers\EmployeeController::edit
 * @see app/Http/Controllers/EmployeeController.php:116
 * @route '/employer/employees/{employee}/edit'
 */
edit.url = (
    args:
        | { employee: string | { id: string } }
        | [employee: string | { id: string }]
        | string
        | { id: string },
    options?: RouteQueryOptions,
) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { employee: args };
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { employee: args.id };
    }

    if (Array.isArray(args)) {
        args = {
            employee: args[0],
        };
    }

    args = applyUrlDefaults(args);

    const parsedArgs = {
        employee:
            typeof args.employee === 'object'
                ? args.employee.id
                : args.employee,
    };

    return (
        edit.definition.url
            .replace('{employee}', parsedArgs.employee.toString())
            .replace(/\/+$/, '') + queryParams(options)
    );
};

/**
 * @see \App\Http\Controllers\EmployeeController::edit
 * @see app/Http/Controllers/EmployeeController.php:116
 * @route '/employer/employees/{employee}/edit'
 */
edit.get = (
    args:
        | { employee: string | { id: string } }
        | [employee: string | { id: string }]
        | string
        | { id: string },
    options?: RouteQueryOptions,
): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
});

/**
 * @see \App\Http\Controllers\EmployeeController::edit
 * @see app/Http/Controllers/EmployeeController.php:116
 * @route '/employer/employees/{employee}/edit'
 */
edit.head = (
    args:
        | { employee: string | { id: string } }
        | [employee: string | { id: string }]
        | string
        | { id: string },
    options?: RouteQueryOptions,
): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
});

/**
 * @see \App\Http\Controllers\EmployeeController::edit
 * @see app/Http/Controllers/EmployeeController.php:116
 * @route '/employer/employees/{employee}/edit'
 */
const editForm = (
    args:
        | { employee: string | { id: string } }
        | [employee: string | { id: string }]
        | string
        | { id: string },
    options?: RouteQueryOptions,
): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
});

/**
 * @see \App\Http\Controllers\EmployeeController::edit
 * @see app/Http/Controllers/EmployeeController.php:116
 * @route '/employer/employees/{employee}/edit'
 */
editForm.get = (
    args:
        | { employee: string | { id: string } }
        | [employee: string | { id: string }]
        | string
        | { id: string },
    options?: RouteQueryOptions,
): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
});

/**
 * @see \App\Http\Controllers\EmployeeController::edit
 * @see app/Http/Controllers/EmployeeController.php:116
 * @route '/employer/employees/{employee}/edit'
 */
editForm.head = (
    args:
        | { employee: string | { id: string } }
        | [employee: string | { id: string }]
        | string
        | { id: string },
    options?: RouteQueryOptions,
): RouteFormDefinition<'get'> => ({
    action: edit.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        },
    }),
    method: 'get',
});

edit.form = editForm;

/**
 * @see \App\Http\Controllers\EmployeeController::update
 * @see app/Http/Controllers/EmployeeController.php:134
 * @route '/employer/employees/{employee}'
 */
export const update = (
    args:
        | { employee: string | { id: string } }
        | [employee: string | { id: string }]
        | string
        | { id: string },
    options?: RouteQueryOptions,
): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
});

update.definition = {
    methods: ['put'],
    url: '/employer/employees/{employee}',
} satisfies RouteDefinition<['put']>;

/**
 * @see \App\Http\Controllers\EmployeeController::update
 * @see app/Http/Controllers/EmployeeController.php:134
 * @route '/employer/employees/{employee}'
 */
update.url = (
    args:
        | { employee: string | { id: string } }
        | [employee: string | { id: string }]
        | string
        | { id: string },
    options?: RouteQueryOptions,
) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { employee: args };
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { employee: args.id };
    }

    if (Array.isArray(args)) {
        args = {
            employee: args[0],
        };
    }

    args = applyUrlDefaults(args);

    const parsedArgs = {
        employee:
            typeof args.employee === 'object'
                ? args.employee.id
                : args.employee,
    };

    return (
        update.definition.url
            .replace('{employee}', parsedArgs.employee.toString())
            .replace(/\/+$/, '') + queryParams(options)
    );
};

/**
 * @see \App\Http\Controllers\EmployeeController::update
 * @see app/Http/Controllers/EmployeeController.php:134
 * @route '/employer/employees/{employee}'
 */
update.put = (
    args:
        | { employee: string | { id: string } }
        | [employee: string | { id: string }]
        | string
        | { id: string },
    options?: RouteQueryOptions,
): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
});

/**
 * @see \App\Http\Controllers\EmployeeController::update
 * @see app/Http/Controllers/EmployeeController.php:134
 * @route '/employer/employees/{employee}'
 */
const updateForm = (
    args:
        | { employee: string | { id: string } }
        | [employee: string | { id: string }]
        | string
        | { id: string },
    options?: RouteQueryOptions,
): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        },
    }),
    method: 'post',
});

/**
 * @see \App\Http\Controllers\EmployeeController::update
 * @see app/Http/Controllers/EmployeeController.php:134
 * @route '/employer/employees/{employee}'
 */
updateForm.put = (
    args:
        | { employee: string | { id: string } }
        | [employee: string | { id: string }]
        | string
        | { id: string },
    options?: RouteQueryOptions,
): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        },
    }),
    method: 'post',
});

update.form = updateForm;

const employees = {
    search: Object.assign(search, search),
    store: Object.assign(store, store),
    edit: Object.assign(edit, edit),
    update: Object.assign(update, update),
};

export default employees;
