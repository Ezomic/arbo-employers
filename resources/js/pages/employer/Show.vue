<script setup lang="ts">
import { Form, Head, router, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { formatDate } from '@/lib/date';
import {
    close as closeAbsence,
    mutate as mutateAbsence,
    store as storeAbsence,
} from '@/routes/absences';
import {
    show as importStatusRoute,
    store as storeImport,
} from '@/routes/employee-imports';
import { store as storeEmployee } from '@/routes/employees';

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

type ContactPerson = {
    id: string;
    name: string;
    email: string | null;
    phone: string | null;
    job_title: string | null;
};

type OpenCase = {
    id: string;
    employee_id: string;
    status: string;
    opened_at: string;
    expected_return_date: string | null;
    employee: { id: string; first_name: string; last_name: string };
};

type CaseTypeOption = { value: string; label: string };

const props = defineProps<{
    employer: Employer;
    contracts: Contract[];
    organizationalUnits: OrganizationalUnit[];
    employees: Employee[];
    openCases: OpenCase[];
    caseTypes: CaseTypeOption[];
    contactPersons: ContactPerson[];
}>();

const unitDepths = computed(() => {
    const depthOf = (unit: OrganizationalUnit): number =>
        unit.parent_id === null
            ? 0
            : 1 +
              depthOf(
                  props.organizationalUnits.find(
                      (u) => u.id === unit.parent_id,
                  )!,
              );

    return new Map(
        props.organizationalUnits.map((unit) => [unit.id, depthOf(unit)]),
    );
});

const page = usePage<{ importId?: number }>();
const pollingImportId = ref<number | null>(page.props.importId ?? null);
const importStatusText = ref<string>('');

function pollImportStatus() {
    if (pollingImportId.value === null) {
        return;
    }

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
        <Heading
            :title="employer.name"
            description="Your company's arrangement with this tenant"
        />

        <section class="grid gap-4 md:grid-cols-2">
            <div class="rounded-lg border p-4">
                <h2 class="mb-4 font-medium">Contracts</h2>
                <ul class="space-y-2">
                    <li
                        v-for="contract in contracts"
                        :key="contract.id"
                        class="flex flex-wrap items-center gap-2 text-sm"
                    >
                        <span class="font-medium">{{
                            contract.contract_type_label ?? 'Contract'
                        }}</span>
                        <span class="text-muted-foreground">
                            {{ formatDate(contract.start_date) }}
                            <template v-if="contract.end_date">
                                — {{ formatDate(contract.end_date) }}</template
                            >
                        </span>
                        <Badge
                            variant="outline"
                            class="capitalize"
                            :class="
                                contract.status === 'active'
                                    ? 'border-transparent bg-emerald-100 text-emerald-800 dark:bg-emerald-500/15 dark:text-emerald-400'
                                    : ''
                            "
                        >
                            {{ contract.status }}
                        </Badge>
                    </li>
                    <li
                        v-if="contracts.length === 0"
                        class="text-sm text-muted-foreground"
                    >
                        No contracts yet.
                    </li>
                </ul>
            </div>

            <div class="rounded-lg border p-4">
                <h2 class="mb-4 font-medium">Organizational units</h2>
                <p class="mb-2 text-xs text-muted-foreground">
                    Managed by your case officer — contact them to add or change
                    units.
                </p>
                <ul class="space-y-2">
                    <li
                        v-for="unit in organizationalUnits"
                        :key="unit.id"
                        class="text-sm"
                        :style="{
                            paddingLeft: `${unitDepths.get(unit.id)! * 1.25}rem`,
                        }"
                    >
                        {{ unit.name }}
                        <span class="text-muted-foreground">
                            ({{
                                unit.is_legal_entity
                                    ? 'legal entity'
                                    : 'internal unit'
                            }})
                        </span>
                    </li>
                    <li
                        v-if="organizationalUnits.length === 0"
                        class="text-sm text-muted-foreground"
                    >
                        No organizational units on file yet.
                    </li>
                </ul>
            </div>

            <div class="rounded-lg border p-4">
                <h2 class="mb-4 font-medium">Contact persons</h2>
                <p class="mb-2 text-xs text-muted-foreground">
                    Managed by your case officer — contact them to add or change
                    contact persons.
                </p>
                <ul class="space-y-2">
                    <li
                        v-for="cp in contactPersons"
                        :key="cp.id"
                        class="text-sm"
                    >
                        <span class="font-medium">{{ cp.name }}</span>
                        <span
                            v-if="cp.job_title"
                            class="ml-2 text-muted-foreground"
                            >{{ cp.job_title }}</span
                        >
                        <span v-if="cp.email" class="ml-2 text-muted-foreground"
                            >· {{ cp.email }}</span
                        >
                        <span v-if="cp.phone" class="ml-2 text-muted-foreground"
                            >· {{ cp.phone }}</span
                        >
                    </li>
                    <li
                        v-if="contactPersons.length === 0"
                        class="text-sm text-muted-foreground"
                    >
                        No contact persons on file yet.
                    </li>
                </ul>
            </div>

            <div
                v-if="openCases.length > 0"
                class="rounded-lg border p-4 md:col-span-2"
            >
                <h2 class="mb-4 font-medium">Open cases</h2>
                <div class="space-y-6">
                    <div
                        v-for="openCase in openCases"
                        :key="openCase.id"
                        class="space-y-3 border-t pt-4 first:border-t-0 first:pt-0"
                    >
                        <p class="text-sm font-medium">
                            {{ openCase.employee.first_name }}
                            {{ openCase.employee.last_name }}
                            <span class="ml-2 text-xs text-muted-foreground">
                                open since {{ formatDate(openCase.opened_at) }}
                                <template v-if="openCase.expected_return_date">
                                    · expected return
                                    {{
                                        formatDate(
                                            openCase.expected_return_date,
                                        )
                                    }}
                                </template>
                            </span>
                        </p>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <Form
                                v-bind="
                                    mutateAbsence.form({ case: openCase.id })
                                "
                                v-slot="{ errors, processing }"
                                class="space-y-2"
                            >
                                <Label
                                    :for="`mut_date_${openCase.id}`"
                                    class="text-xs font-medium"
                                    >Report mutation — expected return
                                    date</Label
                                >
                                <Input
                                    :id="`mut_date_${openCase.id}`"
                                    type="date"
                                    name="expected_return_date"
                                    :default-value="
                                        openCase.expected_return_date ??
                                        undefined
                                    "
                                    class="h-8 text-sm"
                                />
                                <InputError
                                    :message="errors.expected_return_date"
                                />
                                <Button
                                    type="submit"
                                    size="sm"
                                    :disabled="processing"
                                    >Save mutation</Button
                                >
                            </Form>

                            <Form
                                v-bind="
                                    closeAbsence.form({ case: openCase.id })
                                "
                                v-slot="{ errors, processing }"
                                class="space-y-2"
                            >
                                <Label
                                    :for="`rec_date_${openCase.id}`"
                                    class="text-xs font-medium"
                                    >Report recovery — return date</Label
                                >
                                <Input
                                    :id="`rec_date_${openCase.id}`"
                                    type="date"
                                    name="recovery_date"
                                    required
                                    class="h-8 text-sm"
                                />
                                <InputError :message="errors.recovery_date" />
                                <Button
                                    type="submit"
                                    size="sm"
                                    variant="destructive"
                                    :disabled="processing"
                                    >Close case</Button
                                >
                            </Form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border p-4 md:col-span-2">
                <h2 class="mb-4 font-medium">Report absence</h2>
                <p class="mb-4 text-xs text-muted-foreground">
                    Registering the start of an absence course opens a case with
                    your case officer.
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
                            <option value="" disabled selected>
                                Select an employee
                            </option>
                            <option
                                v-for="employee in employees"
                                :key="employee.id"
                                :value="employee.id"
                            >
                                {{ employee.first_name }}
                                {{ employee.last_name }}
                            </option>
                        </select>
                        <InputError :message="errors.employee_id" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="absence_case_type">Type dossier</Label>
                        <select
                            id="absence_case_type"
                            name="case_type"
                            required
                            class="h-9 rounded-md border border-input bg-transparent px-3 text-sm shadow-xs"
                        >
                            <option value="" disabled selected>
                                Select a type
                            </option>
                            <option
                                v-for="type in caseTypes"
                                :key="type.value"
                                :value="type.value"
                            >
                                {{ type.label }}
                            </option>
                        </select>
                        <InputError :message="errors.case_type" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="start_date">Start date</Label>
                        <Input
                            id="start_date"
                            type="date"
                            name="start_date"
                            required
                        />
                        <InputError :message="errors.start_date" />
                    </div>
                    <Button type="submit" :disabled="processing"
                        >Report absence</Button
                    >
                </Form>
            </div>

            <div class="rounded-lg border p-4 md:col-span-2">
                <h2 class="mb-4 font-medium">Employees</h2>
                <ul class="mb-4 space-y-2">
                    <li
                        v-for="employee in employees"
                        :key="employee.id"
                        class="text-sm"
                    >
                        {{ employee.first_name }} {{ employee.last_name }}
                        <span
                            v-if="employee.organizational_unit"
                            class="text-muted-foreground"
                        >
                            ({{ employee.organizational_unit.name }})
                        </span>
                    </li>
                    <li
                        v-if="employees.length === 0"
                        class="text-sm text-muted-foreground"
                    >
                        No employees yet.
                    </li>
                </ul>

                <p
                    v-if="pollingImportId !== null"
                    class="mb-4 text-sm text-muted-foreground"
                >
                    Import #{{ pollingImportId }}:
                    {{ importStatusText || 'processing…' }}
                </p>

                <Form
                    v-bind="storeEmployee.form()"
                    v-slot="{ errors, processing }"
                    :reset-on-success="[
                        'first_name',
                        'last_name',
                        'email',
                        'employee_number',
                    ]"
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
                        <Label for="organizational_unit_id"
                            >Organizational unit</Label
                        >
                        <select
                            id="organizational_unit_id"
                            name="organizational_unit_id"
                            required
                            class="h-9 rounded-md border border-input bg-transparent px-3 text-sm shadow-xs"
                        >
                            <option
                                v-for="unit in organizationalUnits"
                                :key="unit.id"
                                :value="unit.id"
                            >
                                {{ unit.name }}
                            </option>
                        </select>
                        <InputError :message="errors.organizational_unit_id" />
                    </div>
                    <Button type="submit" :disabled="processing"
                        >Add employee</Button
                    >
                </Form>

                <Form
                    v-bind="storeImport.form()"
                    v-slot="{ errors, processing }"
                    class="max-w-md space-y-3 border-t pt-4"
                >
                    <div class="grid gap-2">
                        <Label for="file"
                            >Import roster (CSV, XLSX, or XML)</Label
                        >
                        <Input id="file" type="file" name="file" required />
                        <InputError :message="errors.file" />
                    </div>
                    <Button type="submit" :disabled="processing">Upload</Button>
                </Form>
            </div>
        </section>
    </div>
</template>
