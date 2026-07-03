<script setup lang="ts">
import { Form, Head, router, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { store as storeAbsence } from '@/routes/absences';
import { store as storeEmployee } from '@/routes/employees';
import { show as importStatusRoute, store as storeImport } from '@/routes/employee-imports';

type Employer = { id: string; name: string };
type Contract = {
    id: string;
    contract_type_label: string | null;
    start_date: string;
    end_date: string | null;
    status: string;
};
type OrganizationalUnit = {
    id: string;
    parent_id: string | null;
    name: string;
    is_legal_entity: boolean;
    kvk_number: string | null;
};
type Employee = {
    id: string;
    first_name: string;
    last_name: string;
    email: string | null;
    organizational_unit: OrganizationalUnit | null;
};

const props = defineProps<{
    employer: Employer;
    contracts: Contract[];
    organizationalUnits: OrganizationalUnit[];
    employees: Employee[];
}>();

const unitDepths = computed(() => {
    const depthOf = (unit: OrganizationalUnit): number =>
        unit.parent_id === null
            ? 0
            : 1 + depthOf(props.organizationalUnits.find((u) => u.id === unit.parent_id)!);

    return new Map(props.organizationalUnits.map((unit) => [unit.id, depthOf(unit)]));
});

const page = usePage<{ importId?: number }>();
const pollingImportId = ref<number | null>(page.props.importId ?? null);
const importStatusText = ref<string>('');

function pollImportStatus() {
    if (pollingImportId.value === null) return;

    fetch(importStatusRoute(pollingImportId.value).url)
        .then((r) => r.json())
        .then((data) => {
            importStatusText.value = `${data.status} (${data.success_count}/${data.total_rows})`;

            if (data.status === 'completed' || data.status === 'failed') {
                pollingImportId.value = null;
                router.reload({ only: ['employees'] });
            } else {
                setTimeout(pollImportStatus, 1500);
            }
        });
}

onMounted(() => {
    if (pollingImportId.value !== null) {
        pollImportStatus();
    }
});
</script>

<template>
    <Head :title="employer.name" />

    <div class="flex flex-col gap-8 p-4">
        <Heading :title="employer.name" description="Your company's arrangement with this tenant" />

        <section class="grid gap-4 md:grid-cols-2">
            <div class="rounded-lg border p-4">
                <h2 class="mb-4 font-medium">Contracts</h2>
                <ul class="space-y-2">
                    <li v-for="contract in contracts" :key="contract.id" class="text-sm">
                        {{ contract.contract_type_label }} — {{ contract.start_date }}
                        <span v-if="contract.end_date"> to {{ contract.end_date }}</span>
                        ({{ contract.status }})
                    </li>
                    <li v-if="contracts.length === 0" class="text-sm text-muted-foreground">
                        No contracts yet.
                    </li>
                </ul>
            </div>

            <div class="rounded-lg border p-4">
                <h2 class="mb-4 font-medium">Organizational units</h2>
                <p class="mb-2 text-xs text-muted-foreground">
                    Managed by your case officer — contact them to add or change units.
                </p>
                <ul class="space-y-2">
                    <li
                        v-for="unit in organizationalUnits"
                        :key="unit.id"
                        class="text-sm"
                        :style="{ paddingLeft: `${unitDepths.get(unit.id)! * 1.25}rem` }"
                    >
                        {{ unit.name }}
                        <span class="text-muted-foreground">
                            ({{ unit.is_legal_entity ? 'legal entity' : 'internal unit' }})
                        </span>
                    </li>
                </ul>
            </div>

            <div class="rounded-lg border p-4 md:col-span-2">
                <h2 class="mb-4 font-medium">Report absence</h2>
                <p class="mb-4 text-xs text-muted-foreground">
                    Registering the start of an absence course opens a case with your case officer.
                </p>
                <Form
                    v-bind="storeAbsence.form()"
                    v-slot="{ errors, processing }"
                    :reset-on-success="['start_date']"
                    class="max-w-md space-y-3"
                >
                    <div class="grid gap-2">
                        <Label for="absence_employee_id">Employee</Label>
                        <select
                            id="absence_employee_id"
                            name="employee_id"
                            required
                            class="h-9 rounded-md border border-input bg-transparent px-3 text-sm shadow-xs"
                        >
                            <option value="" disabled selected>Select an employee</option>
                            <option v-for="employee in employees" :key="employee.id" :value="employee.id">
                                {{ employee.first_name }} {{ employee.last_name }}
                            </option>
                        </select>
                        <InputError :message="errors.employee_id" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="start_date">Start date</Label>
                        <Input id="start_date" type="date" name="start_date" required />
                        <InputError :message="errors.start_date" />
                    </div>
                    <Button type="submit" :disabled="processing">Report absence</Button>
                </Form>
            </div>

            <div class="rounded-lg border p-4 md:col-span-2">
                <h2 class="mb-4 font-medium">Employees</h2>
                <ul class="mb-4 space-y-2">
                    <li v-for="employee in employees" :key="employee.id" class="text-sm">
                        {{ employee.first_name }} {{ employee.last_name }}
                        <span class="text-muted-foreground">({{ employee.organizational_unit?.name }})</span>
                    </li>
                    <li v-if="employees.length === 0" class="text-sm text-muted-foreground">
                        No employees yet.
                    </li>
                </ul>

                <p v-if="pollingImportId !== null" class="mb-4 text-sm text-muted-foreground">
                    Import #{{ pollingImportId }}: {{ importStatusText || 'processing…' }}
                </p>

                <Form
                    v-bind="storeEmployee.form()"
                    v-slot="{ errors, processing }"
                    :reset-on-success="['first_name', 'last_name', 'email', 'employee_number']"
                    class="mb-6 max-w-md space-y-3"
                >
                    <div class="grid gap-2">
                        <Label for="first_name">First name</Label>
                        <Input id="first_name" name="first_name" required />
                        <InputError :message="errors.first_name" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="last_name">Last name</Label>
                        <Input id="last_name" name="last_name" required />
                        <InputError :message="errors.last_name" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="email">Email</Label>
                        <Input id="email" type="email" name="email" />
                        <InputError :message="errors.email" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="organizational_unit_id">Organizational unit</Label>
                        <select
                            id="organizational_unit_id"
                            name="organizational_unit_id"
                            required
                            class="h-9 rounded-md border border-input bg-transparent px-3 text-sm shadow-xs"
                        >
                            <option v-for="unit in organizationalUnits" :key="unit.id" :value="unit.id">
                                {{ unit.name }}
                            </option>
                        </select>
                        <InputError :message="errors.organizational_unit_id" />
                    </div>
                    <Button type="submit" :disabled="processing">Add employee</Button>
                </Form>

                <Form
                    v-bind="storeImport.form()"
                    v-slot="{ errors, processing }"
                    class="max-w-md space-y-3 border-t pt-4"
                >
                    <div class="grid gap-2">
                        <Label for="file">Import roster (CSV, XLSX, or XML)</Label>
                        <Input id="file" type="file" name="file" required />
                        <InputError :message="errors.file" />
                    </div>
                    <Button type="submit" :disabled="processing">Upload</Button>
                </Form>
            </div>
        </section>
    </div>
</template>
