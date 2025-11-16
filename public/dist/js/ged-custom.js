// GED Custom JavaScript - Optimized JS
$(function() {
  'use strict';

  // Document Ready Handler
  const GED = {
    init: function() {
      this.initializeFileUploads();
      this.initializeFormValidation();
      this.initializeDataTables();
      this.initializeEventListeners();
    },

    // File Upload Handling
    initializeFileUploads: function() {
      $('.custom-file-input').on('change', function() {
        const fileName = this.files[0]?.name || 'Choisir un fichier';
        $(this).next('.custom-file-label').html(fileName);
        
        if (this.files[0]) {
          this.previewFile(this.files[0]);
        }
      });
    },

    // File Preview
    previewFile: function(file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        const previewElement = $('#file-preview');
        if (file.type === 'application/pdf') {
          previewElement.html(`<embed src="${e.target.result}" type="application/pdf" width="100%" height="200px">`);
        } else if (file.type.startsWith('image/')) {
          previewElement.html(`<img src="${e.target.result}" class="img-fluid" alt="Aperçu">`);
        }
      };
      reader.readAsDataURL(file);
    },

    // Form Validation
    initializeFormValidation: function() {
      $('form').on('submit', function(e) {
        if (!this.checkValidity()) {
          e.preventDefault();
          e.stopPropagation();
        }
        $(this).addClass('was-validated');
      });
    },

    // DataTables Initialization
    initializeDataTables: function() {
      const dataTable = $('.datatable');
      if (dataTable.length) {
        dataTable.DataTable({
          "responsive": true,
          "autoWidth": false,
          "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json"
          },
          "pageLength": 10,
          "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Tous"]],
          "order": [[0, "desc"]]
        });
      }
    },

    // Dynamic Service Population
    updateServices: function(direction) {
      const serviceSelect = $('#agent_service');
      serviceSelect.empty().append('<option value="">Sélectionner un service</option>');
      
      // Example service mapping
      const services = {
        'Direction des Ressources Humaines': ['Service du Personnel', 'Service de la Formation'],
        'Direction Technique': ['Service Technique', 'Service Maintenance'],
        'Direction Administrative': ['Service Administratif', 'Service Juridique']
      };

      if (direction && services[direction]) {
        services[direction].forEach(service => {
          serviceSelect.append(`<option value="${service}">${service}</option>`);
        });
        serviceSelect.prop('disabled', false);
      } else {
        serviceSelect.prop('disabled', true);
      }
    },

    // Event Listeners
    initializeEventListeners: function() {
      // Direction change handler
      $('#agent_direction').on('change', function() {
        GED.updateServices($(this).val());
      });

      // Sidebar toggle for mobile
      $('.nav-toggle').on('click', function() {
        $('body').toggleClass('sidebar-open');
      });

      // Document status updates
      $('.status-update').on('click', function() {
        const docId = $(this).data('doc-id');
        const newStatus = $(this).data('status');
        this.updateDocumentStatus(docId, newStatus);
      });
    },

    // Document Status Update
    updateDocumentStatus: function(docId, status) {
      // Add your status update logic here
      console.log(`Updating document ${docId} to status: ${status}`);
    }
  };

  // Initialize the application
  GED.init();
});