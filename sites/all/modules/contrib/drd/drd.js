(function ($) {

Drupal.drd = Drupal.drd || {};
Drupal.settings.drd = Drupal.settings.drd || {};

Drupal.behaviors.drd = {
  attach: function (context) {
    if (Drupal.settings.drd.urlAjax === undefined) {
      return;
    }
    Drupal.drd.checkStatusAll(context);
    if (Drupal.settings.drd.oids === undefined) {
      Drupal.settings.drd.heartbeat = false;
      Drupal.settings.drd.queueCount = 0;
      Drupal.settings.drd.queueCountRun = 0;
      Drupal.settings.drd.queueCountInfo = 0;
      Drupal.settings.drd.functionCountRun = 0;
      Drupal.settings.drd.oids = [];
      $('div.drd-heartbeat').each(function() {
        Drupal.settings.drd.oids.push($(this).attr('oid'));
      });
    }
    $('.drd-heartbeat-toggle:not(.drd-processed)')
      .addClass('drd-processed')
      .click(function() {
        if (Drupal.settings.drd.heartbeat) {
          Drupal.settings.drd.heartbeat = false;
          clearInterval(Drupal.settings.drd.queueHeartbeat);
          $('.drd-heartbeat-toggle').addClass('off');
          $('.drd-heartbeat-toggle span').attr('title', $('.drd-heartbeat-toggle span').attr('title-off'));
          $('.drd-heartbeat').html('');
        }
        else {
          Drupal.settings.drd.heartbeat = true;
          Drupal.settings.drd.queueHeartbeat = setInterval(Drupal.drd.heartbeat, 10000);
          $('.drd-heartbeat-toggle').removeClass('off');
          $('.drd-heartbeat-toggle span').attr('title', $('.drd-heartbeat-toggle span').attr('title-on'));
          Drupal.drd.heartbeat();
        }
      });
    $('#drd-messages span:not(.drd-processed)')
      .addClass('drd-processed')
      .click(function() {
        $('#drd-messages .content').html('');
        $(this).hide();
        $('#drd-status .content #drd-toolbar').removeClass('messages-available');
      });
    $('.drd-box-messages span.msgs-delete').click(function() {
      $(this.parentNode.parentNode).remove();
      var $id = $(this).attr('id');
      $.get(Drupal.settings.drd.urlAjax + 'messages_seen/' + $id, {}, function(response) {
        var data = Drupal.drd.parse(response);
        return true;
      });
    });
    $('table .form-checkbox:not(.drd-processed)')
      .addClass('drd-processed')
      .change(function() {
        if (this.checked || $('.drd-data.selected').length > 1) {
          $('#edit-options').slideDown('fast');
        }
        else {
          $('#edit-options').slideUp('fast');
        }
      });
    $('#drd-status .content #drd-toolbar:not(.drd-processed)')
      .addClass('drd-processed')
      .click(function() {
        $('#drd-status').toggleClass('active');
      });
    $('.drd-function:not(.drd-processed)')
      .addClass('drd-processed')
      .click(function() {
        Drupal.settings.drd.functionCountRun++;
        $.ajax({
          url: Drupal.settings.drd.urlAjax + 'function/' + Drupal.settings.drd.functionCountRun,
          async: true,
          global: false,
          type: 'POST',
          data: ({
            id: $(this).attr('id'),
            remote: $(this).attr('remote'),
            function: $(this).attr('function'),
            key: $(this).attr('key')
          }),
          dataType: 'html',
          complete: function (response) {
            var data = Drupal.drd.parse(response.response);
          },
          success: function () {
          },
          error: function (XMLHttpRequest, textStatus, errorThrown) {
          }
        });
      });

    Drupal.settings.drd.queueInfo = setInterval(Drupal.drd.info, 1000);
    Drupal.settings.drd.queueRun = setInterval(Drupal.drd.queue, 5000);
    Drupal.settings.drd.queueHeartbeat = setInterval(Drupal.drd.heartbeat, 10000);
    Drupal.drd.info();
    Drupal.drd.queue();
    Drupal.drd.heartbeat();
  }
};

Drupal.drd.queue = function() {
  while (Drupal.settings.drd.queueCount < Drupal.settings.drd.parallelAjaxThreads) {
    Drupal.settings.drd.queueCount++;
    Drupal.settings.drd.queueCountRun++;
    $.ajax({
      url: Drupal.settings.drd.urlAjax + 'queue/run/' + Drupal.settings.drd.queueCountRun,
      processData: false,
      async: true,
      complete: function (response) {
        var data = Drupal.drd.parse(response.response);
        if (data.status == 'OK') {
          clearInterval(Drupal.settings.drd.queueRun);
          Drupal.settings.drd.queueCount = 5000;
        }
        else {
          Drupal.settings.drd.queueCount--;
        }
      }
    });
  }
};

Drupal.drd.info = function() {
  Drupal.settings.drd.queueCountInfo++;
  $.ajax({
    url: Drupal.settings.drd.urlAjax + 'queue/info/' + Drupal.settings.drd.queueCountInfo,
    processData: false,
    async: true,
    complete: function (response) {
      var data = Drupal.drd.parse(response.response);
      var content = '&nbsp;';
      if (data.count == 0) {
        clearInterval(Drupal.settings.drd.queueInfo);
      }
      else {
        content = '<div id="content">' + data.count + ' items in queue' + '</div>';
      }
      $('#drd-queue-info-count').html(content);
      for (var item in data.info) {
        if (typeof data.info[item] == 'string') {
          $('#drd-queue-info').append('<div>' + data.info[item] + '</div>');
        }
        else {
          $('#'+data.info[item].completed).html('OK');
        }

      }
    }
  });
};

Drupal.drd.checkStatusAll = function(context) {
  $('span.drd-status:not(.drd-processed)', context)
    .addClass('drd-processed')
    .each(function () {
      Drupal.drd.checkStatus(this);
    });
};

Drupal.drd.checkStatus = function(elem) {
  var id = $(elem).closest('tr').attr('id');
  $.ajax({
    url: Drupal.settings.drd.urlAjax + 'status/' + id.substr(11),
    processData: false,
    async: true,
    complete: function (response) {
      var data = Drupal.drd.parse(response.response);
      $(elem).html(data.status);
    }
  });
};

Drupal.drd.heartbeat = function() {
  if (!Drupal.settings.drd.heartbeat) {
    return;
  }
  $.each(Drupal.settings.drd.oids, function(i, v) {
    $.ajax({
      url: Drupal.settings.drd.urlAjax + 'heartbeat/' + v,
      processData: false,
      async: true,
      complete: function (response) {
        var data = Drupal.drd.parse(response.response);
        $('.drd-heartbeat[oid="'+v+'"]').html(data.heartbeat);
      }
    });
  });
};

Drupal.drd.parse = function(response) {
  var data;
  if (typeof response == 'string') {
    data = $.parseJSON(response);
  }
  else {
    data = response;
  }
  if (data.messages !== undefined && data.messages !== '') {
    $('#drd-messages .content').append(data.messages);
    $('#drd-messages span').show();
    if (data.messages.search('messages warning') > 0 || data.messages.search('messages error') > 0) {
      $('#drd-status .content #drd-toolbar').addClass('messages-available');
    }
  }
  if (data.refresh_id !== undefined && data.refresh_id !== '') {
    var tds = $('#' + data.refresh_id + ' td');
    if (tds.length > 0) {
      var i = 1;
      for (var item in data.refresh_data) {
        var tx = '';
        if (typeof data.refresh_data[item] == 'string') {
          tx = data.refresh_data[item];
        }
        else {
          tx = data.refresh_data[item].data;
        }
        $(tds[i]).html(tx);
        i++;
      }
      $(tds[0]).addClass('drd-refreshed');
      window.setTimeout(Drupal.drd.clearRefresh, 5000);
      Drupal.drd.checkStatusAll(tds[1]);
    }
    else {
      window.location.reload();
    }
  }
  return data;
};

Drupal.drd.clearRefresh = function() {
  var elements = $('td.drd-refreshed');
  if (elements.length > 0) {
    $(elements[0]).removeClass('drd-refreshed');
  }
};

})(jQuery);
