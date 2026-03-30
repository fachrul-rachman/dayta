<div
    x-data="{
        show: false,
        message: '',
        variant: 'success',
        timeout: null,
        trigger(data) {
            this.message = data.message ?? 'Saved';
            this.variant = data.variant ?? 'success';
            this.show = true;
            clearTimeout(this.timeout);
            this.timeout = setTimeout(() => this.show = false, 3500);
        }
    }"
    x-on:toast.window="trigger($event.detail)"
    x-show="show"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-2"
    x-cloak
    class="fixed bottom-6 right-6 z-50 max-w-sm"
>
    <div
        :class="{
            'border-emerald-300 bg-emerald-50 text-emerald-800 dark:border-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-100': variant === 'success',
            'border-red-300 bg-red-50 text-red-800 dark:border-red-700 dark:bg-red-900/60 dark:text-red-100': variant === 'error',
            'border-amber-300 bg-amber-50 text-amber-800 dark:border-amber-700 dark:bg-amber-900/60 dark:text-amber-100': variant === 'warning',
        }"
        class="flex items-center gap-3 rounded-lg border px-4 py-3 text-sm font-medium shadow-lg"
    >
        <span x-text="message"></span>
        <button @click="show = false" class="ml-auto opacity-60 hover:opacity-100">&times;</button>
    </div>
</div>
