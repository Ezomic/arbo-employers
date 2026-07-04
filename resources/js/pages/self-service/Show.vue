<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { gdprExport } from '@/routes/self-service';

type EmployeeInfo = {
    id: string;
    first_name: string;
    last_name: string;
    email: string | null;
};

type AbsenceCase = {
    id: string;
    status: string;
    opened_at: string | null;
    closed_at: string | null;
    expected_return_date: string | null;
};

const props = defineProps<{
    employee: EmployeeInfo;
    cases: AbsenceCase[];
}>();

function formatDate(dateStr: string | null): string {
    if (!dateStr) return '—';
    return new Date(dateStr).toLocaleDateString('nl-NL', { day: 'numeric', month: 'long', year: 'numeric' });
}
</script>

<template>
    <Head title="Mijn verzuim" />

    <div class="flex flex-col gap-6 p-4">
        <Heading
            :title="`${props.employee.first_name} ${props.employee.last_name}`"
            description="Uw persoonlijke verzuimoverzicht"
        />

        <div class="flex justify-end">
            <a :href="gdprExport().url">
                <Button variant="outline" size="sm">Download uw gegevens (AVG Art. 15)</Button>
            </a>
        </div>

        <div class="rounded-lg border">
            <div class="border-b px-4 py-3">
                <h2 class="font-medium">Verzuimhistorie</h2>
            </div>

            <div v-if="props.cases.length === 0" class="px-4 py-8 text-center text-sm text-muted-foreground">
                Er zijn geen verzuimperiodes geregistreerd.
            </div>

            <table v-else class="w-full text-sm">
                <thead>
                    <tr class="border-b bg-muted/50 text-left">
                        <th class="px-4 py-2 font-medium">Status</th>
                        <th class="px-4 py-2 font-medium">Startdatum</th>
                        <th class="px-4 py-2 font-medium">Einddatum</th>
                        <th class="px-4 py-2 font-medium">Verwachte terugkeer</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="c in props.cases"
                        :key="c.id"
                        class="border-b last:border-0"
                    >
                        <td class="px-4 py-3">
                            <span
                                class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium"
                                :class="c.status === 'open' ? 'bg-amber-100 text-amber-800' : 'bg-green-100 text-green-800'"
                            >
                                {{ c.status === 'open' ? 'Actief' : 'Afgerond' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ formatDate(c.opened_at) }}</td>
                        <td class="px-4 py-3">{{ formatDate(c.closed_at) }}</td>
                        <td class="px-4 py-3">{{ formatDate(c.expected_return_date) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="rounded-lg border p-4 text-sm text-muted-foreground">
            <p class="font-medium text-foreground">Uw rechten</p>
            <p class="mt-1">
                U heeft recht op inzage in uw persoonsgegevens (AVG Art. 15) en het recht op dataportabiliteit (AVG Art. 20).
                Medische gegevens worden beheerd door uw bedrijfsarts en zijn niet opgenomen in dit overzicht (WGBO Art. 456).
                Neem contact op met uw arbo-dienst voor vragen over uw dossier.
            </p>
        </div>
    </div>
</template>
