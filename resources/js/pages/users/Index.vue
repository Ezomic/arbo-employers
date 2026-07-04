<script setup lang="ts">
import { Form, Head, useForm, usePage } from '@inertiajs/vue3';
import { Check, Copy, Plus, Trash2 } from '@lucide/vue';
import { ref } from 'vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { formatDate } from '@/lib/date';
import { destroy, store, update } from '@/routes/users';

type IdentityUser = {
    id: string;
    name: string;
    email: string;
    user_type_id: string;
    role_name: string | null;
    scope_id: string | null;
    created_at: string;
};

defineProps<{
    users: IdentityUser[];
}>();

const page = usePage<{ flash?: { temporaryPassword?: string } }>();

const showCreate = ref(false);
const editingUser = ref<IdentityUser | null>(null);
const deletingUser = ref<IdentityUser | null>(null);
const copied = ref(false);

function confirmDelete() {
    if (!deletingUser.value) {
        return;
    }

    useForm({}).delete(destroy({ uuid: deletingUser.value.id }).url, {
        preserveScroll: true,
        onFinish: () => {
            deletingUser.value = null;
        },
    });
}

async function copyPassword(password: string) {
    await navigator.clipboard.writeText(password);
    copied.value = true;
    setTimeout(() => {
        copied.value = false;
    }, 2000);
}
</script>

<template>
    <Head title="Users" />

    <div class="flex flex-col gap-6 p-4">
        <div class="flex items-center justify-between">
            <Heading
                title="Users"
                description="Manage user accounts for your own employer."
            />
            <Button size="sm" @click="showCreate = true">
                <Plus class="mr-1 size-4" />
                Add user
            </Button>
        </div>

        <!-- Temporary password notice -->
        <div
            v-if="page.props.flash?.temporaryPassword"
            class="rounded-lg border border-yellow-300 bg-yellow-50 p-4 text-sm dark:border-yellow-700 dark:bg-yellow-950"
        >
            <p class="font-medium">
                User created. Share this temporary password with them — it is
                only shown once:
            </p>
            <div class="mt-2 flex items-center gap-2">
                <code
                    class="flex-1 rounded bg-background/60 px-2 py-1 font-mono text-base select-all"
                    >{{ page.props.flash.temporaryPassword }}</code
                >
                <Button
                    variant="outline"
                    size="sm"
                    @click="copyPassword(page.props.flash.temporaryPassword)"
                >
                    <Check v-if="copied" class="size-3.5" />
                    <Copy v-else class="size-3.5" />
                    {{ copied ? 'Copied' : 'Copy' }}
                </Button>
            </div>
        </div>

        <div class="rounded-lg border">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b bg-muted/50">
                        <th class="px-4 py-3 text-left font-medium">Name</th>
                        <th class="px-4 py-3 text-left font-medium">Email</th>
                        <th class="px-4 py-3 text-left font-medium">Since</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="user in users"
                        :key="user.id"
                        class="cursor-pointer border-b last:border-0 hover:bg-muted/30"
                        @click="editingUser = user"
                    >
                        <td class="px-4 py-3 font-medium">{{ user.name }}</td>
                        <td class="px-4 py-3 text-muted-foreground">
                            {{ user.email }}
                        </td>
                        <td class="px-4 py-3 text-muted-foreground">
                            {{ formatDate(user.created_at) }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <Button
                                variant="ghost"
                                size="icon"
                                class="size-7 text-destructive"
                                :aria-label="`Delete ${user.name}`"
                                @click.stop="deletingUser = user"
                            >
                                <Trash2 class="size-3.5" />
                            </Button>
                        </td>
                    </tr>
                    <tr v-if="users.length === 0">
                        <td
                            colspan="4"
                            class="px-4 py-6 text-center text-muted-foreground"
                        >
                            No users yet.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create dialog -->
    <Dialog :open="showCreate" @update:open="(v) => !v && (showCreate = false)">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Add employer user</DialogTitle>
            </DialogHeader>
            <Form
                v-bind="store.form()"
                v-slot="{ errors, processing }"
                :reset-on-success="['name', 'email']"
                class="space-y-4"
                @success="showCreate = false"
            >
                <div class="grid gap-2">
                    <Label for="new_name">Name</Label>
                    <Input id="new_name" name="name" required />
                    <InputError :message="errors.name" />
                </div>
                <div class="grid gap-2">
                    <Label for="new_email">Email</Label>
                    <Input id="new_email" type="email" name="email" required />
                    <InputError :message="errors.email" />
                </div>
                <Button type="submit" :disabled="processing"
                    >Create user</Button
                >
            </Form>
        </DialogContent>
    </Dialog>

    <!-- Edit dialog -->
    <Dialog
        :open="editingUser !== null"
        @update:open="(v) => !v && (editingUser = null)"
    >
        <DialogContent v-if="editingUser">
            <DialogHeader>
                <DialogTitle>Edit user</DialogTitle>
            </DialogHeader>
            <Form
                v-bind="update.form({ uuid: editingUser.id })"
                v-slot="{ errors, processing }"
                class="space-y-4"
                @success="editingUser = null"
            >
                <div class="grid gap-2">
                    <Label for="edit_name">Name</Label>
                    <Input
                        id="edit_name"
                        name="name"
                        :default-value="editingUser.name"
                        required
                    />
                    <InputError :message="errors.name" />
                </div>
                <div class="grid gap-2">
                    <Label for="edit_email">Email</Label>
                    <Input
                        id="edit_email"
                        type="email"
                        name="email"
                        :default-value="editingUser.email"
                        required
                    />
                    <InputError :message="errors.email" />
                </div>
                <div class="flex gap-2">
                    <Button type="submit" :disabled="processing">Save</Button>
                    <Button
                        type="button"
                        variant="outline"
                        @click="editingUser = null"
                        >Cancel</Button
                    >
                </div>
            </Form>
        </DialogContent>
    </Dialog>

    <!-- Delete confirmation dialog -->
    <Dialog
        :open="deletingUser !== null"
        @update:open="(v) => !v && (deletingUser = null)"
    >
        <DialogContent v-if="deletingUser">
            <DialogHeader>
                <DialogTitle>Delete user</DialogTitle>
                <DialogDescription>
                    You are about to delete {{ deletingUser.name }} ({{
                        deletingUser.email
                    }}). They will lose access to the portal. This cannot be
                    undone.
                </DialogDescription>
            </DialogHeader>
            <DialogFooter>
                <Button variant="ghost" @click="deletingUser = null"
                    >Cancel</Button
                >
                <Button variant="destructive" @click="confirmDelete"
                    >Delete user</Button
                >
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
