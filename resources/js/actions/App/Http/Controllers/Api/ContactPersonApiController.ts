import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\ContactPersonApiController::sync
* @see app/Http/Controllers/Api/ContactPersonApiController.php:13
* @route '/api/employers/{employer}/contact-persons'
*/
export const sync = (args: { employer: string | number } | [employer: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: sync.url(args, options),
    method: 'put',
})

sync.definition = {
    methods: ["put"],
    url: '/api/employers/{employer}/contact-persons',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Api\ContactPersonApiController::sync
* @see app/Http/Controllers/Api/ContactPersonApiController.php:13
* @route '/api/employers/{employer}/contact-persons'
*/
sync.url = (args: { employer: string | number } | [employer: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { employer: args }
    }

    if (Array.isArray(args)) {
        args = {
            employer: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        employer: args.employer,
    }

    return sync.definition.url
            .replace('{employer}', parsedArgs.employer.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\ContactPersonApiController::sync
* @see app/Http/Controllers/Api/ContactPersonApiController.php:13
* @route '/api/employers/{employer}/contact-persons'
*/
sync.put = (args: { employer: string | number } | [employer: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: sync.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Api\ContactPersonApiController::sync
* @see app/Http/Controllers/Api/ContactPersonApiController.php:13
* @route '/api/employers/{employer}/contact-persons'
*/
const syncForm = (args: { employer: string | number } | [employer: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: sync.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Api\ContactPersonApiController::sync
* @see app/Http/Controllers/Api/ContactPersonApiController.php:13
* @route '/api/employers/{employer}/contact-persons'
*/
syncForm.put = (args: { employer: string | number } | [employer: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: sync.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

sync.form = syncForm

const ContactPersonApiController = { sync }

export default ContactPersonApiController