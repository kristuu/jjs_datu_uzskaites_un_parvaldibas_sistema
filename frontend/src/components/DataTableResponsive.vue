<template>
    <DataTable v-bind="$attrs">
        <slot/>
        <slot name="header"/>
        <slot name="footer"/>
    </DataTable>
</template>

<script>
import DataTable from 'primevue/datatable';

export default {
    name: 'DataTableResponsive',
    inheritAttrs: false,
    components: {DataTable},
    async mounted() {
        $(this.$el).find('tbody tr td').each(function () {
            $(this).attr('data-label', $(this).closest('table').find('thead tr th').eq($(this).index()).text());
        });
    },
}
</script>

<style scoped>
/* Media query for mobile devices */
@media (max-width: 767px) {
    /* Styles for table cells with data-label attribute */
    :deep(table) tbody td[data-label]::before {
        content: attr(data-label);
        font-weight: bold;
        display: block;
        margin-bottom: 0.25rem;
    }

    /* Hide data-label attribute from mobile view */
    :deep(table) tbody td[data-label] {
        content: "";
        display: none;
    }

    /* Styles for the table container */
    :deep(table) table {
        width: 100% !important;
    }

    /* Styles for the table header */
    :deep(table) thead {
        display: none !important; /* Hide the table header on mobile */
    }

    /* Styles for the table rows */
    :deep(table) tbody {
        display: flex !important;
        flex-direction: column !important;
        align-items: stretch !important;
        min-height: auto !important; /* Adjust the min-height as needed */
    }

    /* Styles for individual table rows (cards) */
    :deep(table) tbody tr {
        border: 1px solid #ccc !important;
        border-radius: 8px !important;
        margin-bottom: 1rem !important;
        background-color: #fff !important;
        padding: 0 0.5rem !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
    }

    /* Remove border from the last td in mobile view */
    :deep(table) tbody td:last-child {
        border: none !important;
    }

    /* Styles for table cells within rows */
    :deep(table) tbody td {
        display: block !important;
        margin: 0.5rem 0 !important;
    }

    /* Styles for action buttons */
    :deep(table) tbody .action-buttons {
        display: flex;
        gap: 0.5rem;
    }
}
</style>
