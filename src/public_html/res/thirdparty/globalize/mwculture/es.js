Globalize.addCultureInfo("es", {
	numberFormat: {
		",": ",",
		".": ".",
		"NaN": "NeuN",
		negativeInfinity: "-Infinito",
		positiveInfinity: "Infinito",
		percent: {
			",": ",",
			".": "."
		},
		currency: {
			pattern: ["-n $","n $"],
			",": ",",
			".": ".",
			symbol: "€"
		}
	},

    messages: {
        // -------------------------------
        // 📌 Comunes
        // -------------------------------
        "Yes": "Sí",
        "No": "No",
        "Accept": "Aceptar",
        "Cancel": "Cancelar",
        "Clear": "Limpiar",
        "Done": "Listo",
        "Loading": "Cargando...",
        "Select": "Seleccionar...",
        "Advanced": "Avanzado",
        "Search": "Buscar...",
        "Back": "Regresar",
        "OK": "OK",
        "New": "Nuevo",

        "%lbl.time.justnow" : "Ahora",
        "%lbl.datetime.minute" : "minuto",
        "%lbl.datetime.minutes" : "minutos",
        "%lbl.datetime.hour" : "hora",
        "%lbl.datetime.hours" : "horas",
        "%lbl.datetime.day" : "día",
        "%lbl.datetime.days" : "días",
        "%lbl.datetime.yesterday" : "Ayer",

        "mw.datetime.relative.past" : [
            "Hace ", {argindex:0}," ",{argindex:1}
        ],

        // -------------------------------
        // 📂 DevExtreme FILE MANAGER
        // -------------------------------

        // Toolbar
        "dxFileManager-newDirectoryName": "Nueva carpeta",
        "dxFileManager-newFolderName": "Nueva carpeta",
        "dxFileManager-renameItem": "Renombrar",
        "dxFileManager-deleteItem": "Eliminar",
        "dxFileManager-createDirectory": "Crear carpeta",
        "dxFileManager-refresh": "Refrescar",
        "dxFileManager-upload": "Subir archivos",
        "dxFileManager-download": "Descargar",
        "dxFileManager-moveItem": "Mover",
        "dxFileManager-copyItem": "Copiar",

        // Dialogos
        "dxFileManager-dialogRenameItemTitle": "Renombrar",
        "dxFileManager-dialogRenameItemText": "Nuevo nombre:",
        "dxFileManager-dialogCreateDirectoryTitle": "Crear nueva carpeta",
        "dxFileManager-dialogCreateDirectoryText": "Nombre de carpeta:",
        "dxFileManager-dialogDeleteItemTitle": "Eliminar",
        "dxFileManager-dialogDeleteItemText": "¿Desea eliminar {0}?",
        "dxFileManager-dialogDeleteMultiItemText": "¿Desea eliminar {0} elementos?",
        "dxFileManager-dialogErrorTitle": "Error",

        // Tipo de item
        "dxFileManager-folder": "Carpeta",
        "dxFileManager-file": "Archivo",
        "dxFileManager-parentDirectory": "Carpeta superior",

        // Columnas
        "dxFileManager-listDetailsColumnCaptionName": "Nombre",
        "dxFileManager-listDetailsColumnCaptionDateModified": "Modificado",
        "dxFileManager-listDetailsColumnCaptionFileSize": "Tamaño",
        "dxFileManager-listThumbnailsTooltipText": "Miniaturas",
        "dxFileManager-listDetailsTooltipText": "Detalles",

        // Errores
        "dxFileManager-errorNoAccess": "Acceso denegado.",
        "dxFileManager-errorDirectoryExists": "Ya existe una carpeta con este nombre.",
        "dxFileManager-errorFileExists": "Ya existe un archivo con este nombre.",
        "dxFileManager-errorFileNotFound": "Archivo no encontrado.",
        "dxFileManager-errorDirectoryNotFound": "Carpeta no encontrada.",
        "dxFileManager-errorWrongFileExtension": "Extensión de archivo no permitida.",
        "dxFileManager-errorMaxFileSizeExceeded": "El tamaño del archivo excede el límite permitido.",
        "dxFileManager-errorDefault": "Error no especificado.",

        // Upload
        "dxFileManager-uploadAbortedMessage": "Subida cancelada.",
        "dxFileManager-uploadFailedMessage": "Error durante la subida.",
        "dxFileManager-uploadSuccessfulMessage": "Archivo subido correctamente.",
        "dxFileManager-uploadInvalidFileExtension": "Tipo de archivo no permitido.",
        "dxFileManager-uploadInvalidMaxFileSize": "El archivo es demasiado grande.",
        "dxFileManager-uploadInvalidMinFileSize": "El archivo es demasiado pequeño.",

        // Progreso
        "dxFileManager-progressBarLabel": "Progreso",
        "dxFileManager-progressBarCancel": "Cancelar",

        // Selección
        "dxFileManager-selectAll": "Seleccionar todo",
        "dxFileManager-deselectAll": "Deseleccionar todo"
    },

	calendar: {
		patterns: { x:"d-M-yyyy H:mm" }
	},
	calendars: {
		standard: {
			patterns: { x:"d-M-yyyy H:mm" }
		}
	}

});
