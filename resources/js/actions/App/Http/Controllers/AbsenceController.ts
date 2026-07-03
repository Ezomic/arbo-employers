import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\AbsenceController::store
* @see app/Http/Controllers/AbsenceController.php:19
* @route '/employer/absences'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/employer/absences',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AbsenceController::store
* @see app/Http/Controllers/AbsenceController.php:19
* @route '/employer/absences'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AbsenceController::store
* @see app/Http/Controllers/AbsenceController.php:19
* @route '/employer/absences'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\AbsenceController::store
* @see app/Http/Controllers/AbsenceController.php:19
* @route '/employer/absences'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\AbsenceController::store
* @see app/Http/Controllers/AbsenceController.php:19
* @route '/employer/absences'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

const AbsenceController = { store }

export default AbsenceController