jQuery(document).ready(function( $ ) {
    $(".octo-category-filter").select2();
    theme: "classic"
    $('select').select2({
      placeholder: {
        id: '-1', // the value of the option
        text: 'Search and pick a term...'
      }
    });
  });

  jQuery(document).ready(function( $ ) {
    $(".octo-post-filter").select2({
      placeholder: "Search and pick a post..."
    });
  });

  jQuery(document).ready(function( $ ) {
    $(".octo-page-filter").select2({
      placeholder: "Search and pick a page..."
    });
  });