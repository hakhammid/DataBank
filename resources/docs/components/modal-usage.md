# Reusable Modal Component

The `x-my-modal` component provides a customizable, accessible modal dialog that can be used throughout your application.

## Basic Usage

```blade
<button data-modal-target="example-modal">Open Modal</button>

<x-my-modal id="example-modal" title="Example Modal">
    <p>This is the content of the modal.</p>
    
    <x-slot name="footer">
        <button data-modal-close type="button" class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">Close</button>
    </x-slot>
</x-my-modal>
```

## Props

The modal component accepts the following props:

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string | `'custom-modal'` | Unique identifier for the modal |
| `title` | string | `'Modal Title'` | The title displayed in the modal header |
| `showIcon` | boolean | `true` | Whether to show an icon in the modal header |
| `iconType` | string | `'warning'` | Type of icon to show. Options: `'warning'`, `'info'`, `'success'`, `'question'` |
| `maxWidth` | string | `'lg'` | Maximum width of the modal. Options: `'sm'`, `'md'`, `'lg'`, `'xl'`, `'2xl'`, `'3xl'`, `'4xl'`, `'5xl'`, `'6xl'`, `'7xl'` |

## Slots

The component accepts two slots:

1. The default slot for the modal content
2. A named `footer` slot for the modal footer buttons

## Opening and Closing the Modal

### Opening the Modal

To open a modal, create a button with a `data-modal-target` attribute that matches the modal's `id`:

```blade
<button data-modal-target="my-modal-id">Open Modal</button>
```

### Closing the Modal

To create a close button inside the modal, add a `data-modal-close` attribute:

```blade
<button data-modal-close type="button">Close</button>
```

The modal can also be closed by:
- Pressing the ESC key
- Clicking outside the modal

## Example 1: Confirmation Modal

```blade
<x-my-modal id="delete-modal" title="Delete Item" iconType="warning">
    <p class="text-sm text-gray-500">
        Are you sure you want to delete this item? This action cannot be undone.
    </p>
    
    <x-slot name="footer">
        <button data-modal-close type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">Delete</button>
        <button data-modal-close type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
    </x-slot>
</x-my-modal>
```

## Example 2: Form Modal

```blade
<x-my-modal id="edit-modal" title="Edit Item" iconType="info">
    <form id="edit-form" class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
        </div>
    </form>
    
    <x-slot name="footer">
        <button type="submit" form="edit-form" class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">Save</button>
        <button data-modal-close type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
    </x-slot>
</x-my-modal>
```

## Example 3: Modal Without Icon

```blade
<x-my-modal id="notification-modal" title="Notification" :showIcon="false">
    <p>Your changes have been saved successfully.</p>
    
    <x-slot name="footer">
        <button data-modal-close type="button" class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">OK</button>
    </x-slot>
</x-my-modal>
```

## JavaScript Integration

If you need to open or close the modal programmatically, you can use JavaScript:

```js
// Open modal
document.getElementById('my-modal-id').classList.remove('hidden');
document.body.classList.add('overflow-hidden');

// Close modal
document.getElementById('my-modal-id').classList.add('hidden');
document.body.classList.remove('overflow-hidden');
```

## Handling Form Submissions

When including forms in your modal, you can handle submissions like this:

```js
document.getElementById('my-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Process form data
    const formData = new FormData(this);
    
    // Close the modal after submission
    document.getElementById('my-modal-id').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
});
``` 