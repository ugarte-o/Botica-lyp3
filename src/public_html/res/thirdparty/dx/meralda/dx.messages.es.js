/*!
* Meralda override for DevExtreme Spanish messages.
* Load AFTER dx.messages.es.js
* Only includes keys that were still in English (or had typos).
*/
"use strict";

(function (root, factory) {
    if (typeof define === "function" && define.amd) {
        define(function (require) {
            factory(require("devextreme/localization"));
        });
    } else if (typeof module === "object" && module.exports) {
        factory(require("devextreme/localization"));
    } else {
        factory(DevExpress.localization);
    }
}(0, function (localization) {

    localization.loadMessages({
        es: {

            // -------------------------------------------------
            // dxDateRangeBox
            // -------------------------------------------------
            "dxDateRangeBox-invalidStartDateMessage": "El valor inicial debe ser una fecha",
            "dxDateRangeBox-invalidEndDateMessage": "El valor final debe ser una fecha",
            "dxDateRangeBox-startDateOutOfRangeMessage": "La fecha inicial está fuera de rango",
            "dxDateRangeBox-endDateOutOfRangeMessage": "La fecha final está fuera de rango",

            // -------------------------------------------------
            // dxFileUploader (typos/English)
            // -------------------------------------------------
            "dxFileUploader-uploadAbortedMessage": "Subida cancelada",
            "dxFileUploader-uploadFailedMessage": "Error al subir el archivo",

            // -------------------------------------------------
            // dxDataGrid (English leftovers)
            // -------------------------------------------------
            "dxDataGrid-emptyHeaderWithColumnChooserText": "Use {0} para mostrar columnas",
            "dxDataGrid-emptyHeaderWithGroupPanelText": "Arrastre una columna del panel de grupo aquí",
            "dxDataGrid-emptyHeaderWithColumnChooserAndGroupPanelText": "Use {0} o arrastre una columna desde el panel de grupo",
            "dxDataGrid-emptyHeaderColumnChooserText": "selector de columnas",
            "dxDataGrid-ariaSearchBox": "Caja de búsqueda",
            "dxDataGrid-headerFilterLabel": "Opciones de filtro",
            "dxDataGrid-headerFilterIndicatorLabel": "Mostrar opciones de filtro para la columna '{0}'",
            "dxDataGrid-ariaAdaptiveCollapse": "Ocultar datos adicionales",
            "dxDataGrid-ariaAdaptiveExpand": "Mostrar datos adicionales",
            "dxDataGrid-ariaToolbar": "Barra de herramientas de la tabla",
            "dxDataGrid-ariaEditForm": "Formulario de edición",

            
           
            
            
            "dxDataGrid-columnChooserTitle": "Selector de Columnas",
            "dxDataGrid-columnChooserEmptyText": "Arrastra una columna aqu\xed para ocultarla",
            "dxDataGrid-groupContinuesMessage": "Contin\xfaa en la p\xe1gina siguiente",
            "dxDataGrid-groupContinuedMessage": "Continuaci\xf3n de la p\xe1gina anterior",
            "dxDataGrid-groupHeaderText": "Agrupar por esta columna",
            "dxDataGrid-ungroupHeaderText": "Desagrupar",
            "dxDataGrid-ungroupAllText": "Desagrupar Todo",
            "dxDataGrid-editingEditRow": "Modificar",
            "dxDataGrid-editingSaveRowChanges": "Guardar",
            "dxDataGrid-editingCancelRowChanges": "Cancelar",
            "dxDataGrid-editingDeleteRow": "Eliminar",
            "dxDataGrid-editingUndeleteRow": "Recuperar",
            "dxDataGrid-editingConfirmDeleteMessage": "\xbfEst\xe1 seguro que desea eliminar este registro?",
            "dxDataGrid-validationCancelChanges": "Cancelar cambios",
            "dxDataGrid-groupPanelEmptyText": "Arrastra una columna aqu\xed para agrupar por ella",
            "dxDataGrid-noDataText": "Sin datos",
            "dxDataGrid-searchPanelPlaceholder": "Buscar...",
            "dxDataGrid-filterRowShowAllText": "(Todos)",
            "dxDataGrid-filterRowResetOperationText": "Reestablecer",
            "dxDataGrid-filterRowOperationEquals": "Igual",
            "dxDataGrid-filterRowOperationNotEquals": "No es igual",
            "dxDataGrid-filterRowOperationLess": "Menor que",
            "dxDataGrid-filterRowOperationLessOrEquals": "Menor o igual a",
            "dxDataGrid-filterRowOperationGreater": "Mayor que",
            "dxDataGrid-filterRowOperationGreaterOrEquals": "Mayor o igual a",
            "dxDataGrid-filterRowOperationStartsWith": "Empieza con",
            "dxDataGrid-filterRowOperationContains": "Contiene",
            "dxDataGrid-filterRowOperationNotContains": "No contiene",
            "dxDataGrid-filterRowOperationEndsWith": "Termina con",
            "dxDataGrid-filterRowOperationBetween": "Entre",
            "dxDataGrid-filterRowOperationBetweenStartText": "Inicio",
            "dxDataGrid-filterRowOperationBetweenEndText": "Fin",
            "dxDataGrid-ariaSearchBox": "Search box",
            "dxDataGrid-applyFilterText": "Filtrar",
            "dxDataGrid-trueText": "verdadero",
            "dxDataGrid-falseText": "falso",
            "dxDataGrid-sortingAscendingText": "Orden Ascendente",
            "dxDataGrid-sortingDescendingText": "Orden Descendente",
            "dxDataGrid-sortingClearText": "Limpiar Ordenamiento",
            "dxDataGrid-editingSaveAllChanges": "Guardar cambios",
            "dxDataGrid-editingCancelAllChanges": "Descartar cambios",
            "dxDataGrid-editingAddRow": "Agregar una fila",
            "dxDataGrid-summaryMin": "M\xedn: {0}",
            "dxDataGrid-summaryMinOtherColumn": "M\xedn de {1} es {0}",
            "dxDataGrid-summaryMax": "M\xe1x: {0}",
            "dxDataGrid-summaryMaxOtherColumn": "M\xe1x de {1} es {0}",
            "dxDataGrid-summaryAvg": "Prom: {0}",
            "dxDataGrid-summaryAvgOtherColumn": "Prom de {1} es {0}",
            "dxDataGrid-summarySum": "Suma: {0}",
            "dxDataGrid-summarySumOtherColumn": "Suma de {1} es {0}",
            "dxDataGrid-summaryCount": "Cantidad: {0}",
            "dxDataGrid-columnFixingFix": "Anclar",
            "dxDataGrid-columnFixingUnfix": "Desanclar",
            "dxDataGrid-columnFixingLeftPosition": "A la izquierda",
            "dxDataGrid-columnFixingRightPosition": "A la derecha",
            "dxDataGrid-exportTo": "Exportar",
            "dxDataGrid-exportToExcel": "Exportar a archivo Excel",
            "dxDataGrid-exporting": "Exportar...",
            "dxDataGrid-excelFormat": "Archivo Excel",
            "dxDataGrid-selectedRows": "Filas seleccionadas",
            "dxDataGrid-exportSelectedRows": "Exportar filas seleccionadas a {0}",
            "dxDataGrid-exportAll": "Exportar todo a {0}",
            "dxDataGrid-headerFilterLabel": "Filter options",
            "dxDataGrid-headerFilterIndicatorLabel": "Show filter options for column '{0}'",
            "dxDataGrid-headerFilterEmptyValue": "(Vacio)",
            "dxDataGrid-headerFilterOK": "Aceptar",
            "dxDataGrid-headerFilterCancel": "Cancelar",
            "dxDataGrid-ariaAdaptiveCollapse": "Hide additional data",
            "dxDataGrid-ariaAdaptiveExpand": "Display additional data",
            "dxDataGrid-ariaColumn": "Columna",
            "dxDataGrid-ariaValue": "Valor",
            "dxDataGrid-ariaFilterCell": "Celda de filtro",
            "dxDataGrid-ariaCollapse": "Colapsar",
            "dxDataGrid-ariaExpand": "Expandir",
            "dxDataGrid-ariaDataGrid": "Tabla de datos",
            "dxDataGrid-ariaSearchInGrid": "Buscar en la tabla de datos",
            "dxDataGrid-ariaSelectAll": "Seleccionar todo",
            "dxDataGrid-ariaSelectRow": "Seleccionar fila",
            "dxDataGrid-ariaToolbar": "Data grid toolbar",
            "dxDataGrid-ariaEditForm": "Edit form",
            "dxDataGrid-filterBuilderPopupTitle": "Constructor de filtro",
            "dxDataGrid-filterPanelCreateFilter": "Crear filtro",
            "dxDataGrid-filterPanelClearFilter": "Limpiar filtro",
            "dxDataGrid-filterPanelFilterEnabledHint": "Habilitar filtro",

            // -------------------------------------------------
            // dxTreeList (English leftovers)
            // -------------------------------------------------
            "dxTreeList-ariaTreeList": "Lista en árbol con {0} filas y {1} columnas",
            "dxTreeList-ariaSearchInGrid": "Buscar en la lista en árbol",
            "dxTreeList-ariaToolbar": "Barra de herramientas de la lista en árbol",

            // -------------------------------------------------
            // dxPager (English leftovers)
            // -------------------------------------------------
            "dxPager-pageSize": "Ítems por página: {0}",
            "dxPager-page": "Página {0}",
            "dxPager-prevPage": "Página anterior",
            "dxPager-nextPage": "Página siguiente",
            "dxPager-ariaLabel": "Navegación de páginas",
            "dxPager-ariaPageSize": "Tamaño de página",
            "dxPager-ariaPageNumber": "Número de página",

            // -------------------------------------------------
            // dxScheduler (English leftovers)
            // -------------------------------------------------
            "dxScheduler-recurrenceMinutely": "Cada minuto",
            "dxScheduler-recurrenceHourly": "Cada hora",
            "dxScheduler-recurrenceRepeatOn": "Repetir en",
            "dxScheduler-recurrenceRepeatMinutely": "minuto(s)",
            "dxScheduler-recurrenceRepeatHourly": "hora(s)",

            // -------------------------------------------------
            // dxCalendar (large English aria tip)
            // -------------------------------------------------
            "dxCalendar-ariaHotKeysInfo":
                "Para navegar entre vistas, presione Control y luego Flecha Izquierda o Flecha Derecha. " +
                "Para acercar una vista, presione Control y luego Flecha Abajo. " +
                "Para alejar una vista, presione Control y luego Flecha Arriba.",

            // -------------------------------------------------
            // dxHtmlEditor (many English leftovers)
            // -------------------------------------------------
            "dxHtmlEditor-dialogInsertTableRowsField": "Filas",
            "dxHtmlEditor-dialogInsertTableColumnsField": "Columnas",
            "dxHtmlEditor-dialogInsertTableCaption": "Insertar tabla",
            "dxHtmlEditor-dialogUpdateImageCaption": "Actualizar imagen",
            "dxHtmlEditor-dialogImageUpdateButton": "Actualizar",
            "dxHtmlEditor-dialogImageAddButton": "Agregar",
            "dxHtmlEditor-dialogImageSpecifyUrl": "Desde la web",
            "dxHtmlEditor-dialogImageSelectFile": "Desde este dispositivo",
            "dxHtmlEditor-dialogImageKeepAspectRatio": "Mantener proporción",
            "dxHtmlEditor-dialogImageEncodeToBase64": "Codificar a Base64",
            "dxHtmlEditor-background": "Color de fondo",
            "dxHtmlEditor-bold": "Negrita",
            "dxHtmlEditor-color": "Color de fuente",
            "dxHtmlEditor-font": "Fuente",
            "dxHtmlEditor-italic": "Cursiva",
            "dxHtmlEditor-link": "Agregar enlace",
            "dxHtmlEditor-image": "Agregar imagen",
            "dxHtmlEditor-size": "Tamaño",
            "dxHtmlEditor-strike": "Tachado",
            "dxHtmlEditor-subscript": "Subíndice",
            "dxHtmlEditor-superscript": "Superíndice",
            "dxHtmlEditor-underline": "Subrayado",
            "dxHtmlEditor-blockquote": "Cita",
            "dxHtmlEditor-header": "Encabezado",
            "dxHtmlEditor-increaseIndent": "Aumentar sangría",
            "dxHtmlEditor-decreaseIndent": "Disminuir sangría",
            "dxHtmlEditor-orderedList": "Lista ordenada",
            "dxHtmlEditor-bulletList": "Lista con viñetas",
            "dxHtmlEditor-alignLeft": "Alinear a la izquierda",
            "dxHtmlEditor-alignCenter": "Alinear al centro",
            "dxHtmlEditor-alignRight": "Alinear a la derecha",
            "dxHtmlEditor-alignJustify": "Justificar",
            "dxHtmlEditor-codeBlock": "Bloque de código",
            "dxHtmlEditor-variable": "Agregar variable",
            "dxHtmlEditor-undo": "Deshacer",
            "dxHtmlEditor-redo": "Rehacer",
            "dxHtmlEditor-clear": "Limpiar formato",
            "dxHtmlEditor-insertTable": "Insertar tabla",
            "dxHtmlEditor-insertHeaderRow": "Insertar fila de encabezado",
            "dxHtmlEditor-insertRowAbove": "Insertar fila arriba",
            "dxHtmlEditor-insertRowBelow": "Insertar fila abajo",
            "dxHtmlEditor-insertColumnLeft": "Insertar columna a la izquierda",
            "dxHtmlEditor-insertColumnRight": "Insertar columna a la derecha",
            "dxHtmlEditor-deleteColumn": "Eliminar columna",
            "dxHtmlEditor-deleteRow": "Eliminar fila",
            "dxHtmlEditor-deleteTable": "Eliminar tabla",
            "dxHtmlEditor-cellProperties": "Propiedades de celda",
            "dxHtmlEditor-tableProperties": "Propiedades de tabla",
            "dxHtmlEditor-border": "Borde",
            "dxHtmlEditor-style": "Estilo",
            "dxHtmlEditor-width": "Ancho",
            "dxHtmlEditor-height": "Alto",
            "dxHtmlEditor-borderColor": "Color",
            "dxHtmlEditor-tableBackground": "Fondo",
            "dxHtmlEditor-dimensions": "Dimensiones",
            "dxHtmlEditor-alignment": "Alineación",
            "dxHtmlEditor-horizontal": "Horizontal",
            "dxHtmlEditor-vertical": "Vertical",
            "dxHtmlEditor-paddingVertical": "Relleno vertical",
            "dxHtmlEditor-paddingHorizontal": "Relleno horizontal",
            "dxHtmlEditor-pixels": "Píxeles",
            "dxHtmlEditor-list": "Lista",
            "dxHtmlEditor-ordered": "Ordenada",
            "dxHtmlEditor-bullet": "Viñetas",
            "dxHtmlEditor-align": "Alinear",
            "dxHtmlEditor-center": "Centro",
            "dxHtmlEditor-left": "Izquierda",
            "dxHtmlEditor-right": "Derecha",
            "dxHtmlEditor-indent": "Sangría",
            "dxHtmlEditor-justify": "Justificar",

            // -------------------------------------------------
            // dxFileManager (English leftovers)
            // -------------------------------------------------
            "dxFileManager-rootDirectoryName": "Archivos",
            "dxFileManager-commandRootDirectory": "Archivos",
            
            "dxFileManager-errorDirectoryNotFoundFormat": "Carpeta '{0}' no encontrada.",
            "dxFileManager-errorWrongFileExtension": "La extensión del archivo no está permitida.",
            "dxFileManager-errorMaxFileSizeExceeded": "El archivo supera el tamaño máximo permitido.",
            "dxFileManager-errorInvalidSymbols": "Este nombre contiene caracteres no válidos.",
            "dxFileManager-errorDirectoryOpenFailed": "No se puede abrir la carpeta.",
            "dxFileManager-commandCreate": "Nueva carpeta",
            "dxFileManager-commandRename": "Renombrar",
            "dxFileManager-commandMove": "Mover a",
            "dxFileManager-commandCopy": "Copiar a",
            "dxFileManager-commandDelete": "Eliminar",
            "dxFileManager-commandDownload": "Descargar",
            "dxFileManager-commandUpload": "Subir archivos",
            "dxFileManager-commandRefresh": "Refrescar",
            "dxFileManager-commandThumbnails": "Vista de miniaturas",
            "dxFileManager-commandDetails": "Vista de detalles",
            "dxFileManager-commandClearSelection": "Limpiar selección",
            "dxFileManager-commandShowNavPane": "Mostrar/ocultar panel de navegación",
            "dxFileManager-dialogDirectoryChooserMoveTitle": "Mover a",
            "dxFileManager-dialogDirectoryChooserMoveButtonText": "Mover",
            "dxFileManager-dialogDirectoryChooserCopyTitle": "Copiar a",
            "dxFileManager-dialogDirectoryChooserCopyButtonText": "Copiar",
            "dxFileManager-dialogRenameItemTitle": "Renombrar",
            "dxFileManager-dialogRenameItemButtonText": "Guardar",
            "dxFileManager-dialogCreateDirectoryTitle": "Nueva carpeta",
            "dxFileManager-dialogCreateDirectoryButtonText": "Crear",
            "dxFileManager-dialogDeleteItemTitle": "Eliminar",
            "dxFileManager-dialogDeleteItemButtonText": "Eliminar",
            "dxFileManager-dialogDeleteItemSingleItemConfirmation": "¿Seguro que desea eliminar {0}?",
            "dxFileManager-dialogDeleteItemMultipleItemsConfirmation": "¿Seguro que desea eliminar {0} elementos?",
            "dxFileManager-dialogButtonCancel": "Cancelar",
            "dxFileManager-editingCreateSingleItemProcessingMessage": "Creando una carpeta dentro de {0}",
            "dxFileManager-editingCreateSingleItemSuccessMessage": "Se creó una carpeta dentro de {0}",
            "dxFileManager-editingCreateSingleItemErrorMessage": "No se pudo crear la carpeta",
            "dxFileManager-editingCreateCommonErrorMessage": "No se pudo crear la carpeta",
            "dxFileManager-editingRenameSingleItemProcessingMessage": "Renombrando un elemento dentro de {0}",
            "dxFileManager-editingRenameSingleItemSuccessMessage": "Se renombró un elemento dentro de {0}",
            "dxFileManager-editingRenameSingleItemErrorMessage": "No se pudo renombrar el elemento",
            "dxFileManager-editingRenameCommonErrorMessage": "No se pudo renombrar el elemento",
            "dxFileManager-editingDeleteSingleItemProcessingMessage": "Eliminando un elemento de {0}",
            "dxFileManager-editingDeleteMultipleItemsProcessingMessage": "Eliminando {0} elementos de {1}",
            "dxFileManager-editingDeleteSingleItemSuccessMessage": "Se eliminó un elemento de {0}",
            "dxFileManager-editingDeleteMultipleItemsSuccessMessage": "Se eliminaron {0} elementos de {1}",
            "dxFileManager-editingDeleteSingleItemErrorMessage": "No se pudo eliminar el elemento",
            "dxFileManager-editingDeleteMultipleItemsErrorMessage": "No se pudieron eliminar {0} elementos",
            "dxFileManager-editingDeleteCommonErrorMessage": "No se pudieron eliminar algunos elementos",
            "dxFileManager-editingMoveSingleItemProcessingMessage": "Moviendo un elemento a {0}",
            "dxFileManager-editingMoveMultipleItemsProcessingMessage": "Moviendo {0} elementos a {1}",
            "dxFileManager-editingMoveSingleItemSuccessMessage": "Se movió un elemento a {0}",
            "dxFileManager-editingMoveMultipleItemsSuccessMessage": "Se movieron {0} elementos a {1}",
            "dxFileManager-editingMoveSingleItemErrorMessage": "No se pudo mover el elemento",
            "dxFileManager-editingMoveMultipleItemsErrorMessage": "No se pudieron mover {0} elementos",
            "dxFileManager-editingMoveCommonErrorMessage": "No se pudieron mover algunos elementos",
            "dxFileManager-editingCopySingleItemProcessingMessage": "Copiando un elemento a {0}",
            "dxFileManager-editingCopyMultipleItemsProcessingMessage": "Copiando {0} elementos a {1}",
            "dxFileManager-editingCopySingleItemSuccessMessage": "Se copió un elemento a {0}",
            "dxFileManager-editingCopyMultipleItemsSuccessMessage": "Se copiaron {0} elementos a {1}",
            "dxFileManager-editingCopySingleItemErrorMessage": "No se pudo copiar el elemento",
            "dxFileManager-editingCopyMultipleItemsErrorMessage": "No se pudieron copiar {0} elementos",
            "dxFileManager-editingCopyCommonErrorMessage": "No se pudieron copiar algunos elementos",
            "dxFileManager-editingUploadSingleItemProcessingMessage": "Subiendo un elemento a {0}",
            "dxFileManager-editingUploadMultipleItemsProcessingMessage": "Subiendo {0} elementos a {1}",
            "dxFileManager-editingUploadSingleItemSuccessMessage": "Se subió un elemento a {0}",
            "dxFileManager-editingUploadMultipleItemsSuccessMessage": "Se subieron {0} elementos a {1}",
            "dxFileManager-editingUploadSingleItemErrorMessage": "No se pudo subir el elemento",
            "dxFileManager-editingUploadMultipleItemsErrorMessage": "No se pudieron subir {0} elementos",
            "dxFileManager-editingUploadCanceledMessage": "Cancelado",
            "dxFileManager-editingDownloadSingleItemErrorMessage": "No se pudo descargar el elemento",
            "dxFileManager-editingDownloadMultipleItemsErrorMessage": "No se pudieron descargar {0} elementos",
            "dxFileManager-listDetailsColumnCaptionName": "Nombre",
            "dxFileManager-listDetailsColumnCaptionDateModified": "Fecha de modificación",
            "dxFileManager-listDetailsColumnCaptionFileSize": "Tamaño de archivo",
            "dxFileManager-listThumbnailsTooltipTextSize": "Tamaño",
            "dxFileManager-listThumbnailsTooltipTextDateModified": "Fecha de modificación",
            "dxFileManager-notificationProgressPanelTitle": "Progreso",
            "dxFileManager-notificationProgressPanelEmptyListText": "Sin operaciones",
            "dxFileManager-notificationProgressPanelOperationCanceled": "Cancelado",

            // -------------------------------------------------
            // dxGantt (English leftovers at the end)
            // -------------------------------------------------
            "dxGantt-dialogTitle": "Título",
            "dxGantt-dialogStartTitle": "Inicio",
            "dxGantt-dialogEndTitle": "Fin",
            "dxGantt-dialogProgressTitle": "Progreso",
            "dxGantt-dialogResourcesTitle": "Recursos",
            "dxGantt-dialogResourceManagerTitle": "Administrador de recursos",
            "dxGantt-dialogTaskDetailsTitle": "Detalles de la tarea",
            "dxGantt-dialogEditResourceListHint": "Editar lista de recursos",
            "dxGantt-dialogEditNoResources": "Sin recursos",
            "dxGantt-dialogButtonAdd": "Agregar",
            "dxGantt-contextMenuNewTask": "Nueva tarea",
            "dxGantt-contextMenuNewSubtask": "Nueva subtarea",
            "dxGantt-contextMenuDeleteTask": "Eliminar tarea",
            "dxGantt-contextMenuDeleteDependency": "Eliminar dependencia",
            "dxGantt-dialogTaskDeleteConfirmation":
                "Eliminar una tarea también elimina sus dependencias y subtareas. ¿Seguro que desea eliminar esta tarea?",
            "dxGantt-dialogDependencyDeleteConfirmation":
                "¿Seguro que desea eliminar la dependencia de la tarea?",
            "dxGantt-dialogResourcesDeleteConfirmation":
                "Eliminar un recurso también lo elimina de las tareas a las que está asignado. " +
                "¿Seguro que desea eliminar estos recursos? Recursos: {0}",
            "dxGantt-dialogConstraintCriticalViolationMessage":
                "La tarea que intenta mover está enlazada a una segunda tarea por una dependencia. " +
                "Este cambio entra en conflicto con las reglas de dependencias. ¿Cómo desea proceder?",
            "dxGantt-dialogConstraintViolationMessage":
                "La tarea que intenta mover está enlazada a una segunda tarea por una dependencia. " +
                "¿Cómo desea proceder?",
            "dxGantt-dialogCancelOperationMessage": "Cancelar la operación",
            "dxGantt-dialogDeleteDependencyMessage": "Eliminar la dependencia",
            "dxGantt-dialogMoveTaskAndKeepDependencyMessage": "Mover la tarea y mantener la dependencia",
            "dxGantt-dialogConstraintCriticalViolationSeveralTasksMessage":
                "La tarea que intenta mover está enlazada a otras tareas por dependencias. " +
                "Este cambio entra en conflicto con las reglas de dependencias. ¿Cómo desea proceder?",
            "dxGantt-dialogConstraintViolationSeveralTasksMessage":
                "La tarea que intenta mover está enlazada a otras tareas por dependencias. ¿Cómo desea proceder?",
            "dxGantt-dialogDeleteDependenciesMessage": "Eliminar las dependencias",
            "dxGantt-dialogMoveTaskAndKeepDependenciesMessage": "Mover la tarea y mantener las dependencias",
            "dxGantt-undo": "Deshacer",
            "dxGantt-redo": "Rehacer",
            "dxGantt-expandAll": "Expandir todo",
            "dxGantt-collapseAll": "Colapsar todo",
            "dxGantt-addNewTask": "Agregar nueva tarea",
            "dxGantt-deleteSelectedTask": "Eliminar tarea seleccionada",
            "dxGantt-zoomIn": "Acercar",
            "dxGantt-zoomOut": "Alejar",
            "dxGantt-fullScreen": "Pantalla completa"
        }
    });

}));
