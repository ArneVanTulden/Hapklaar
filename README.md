# Hapklaar

Hapklaar is een receptenplatform dat koken makkelijker maakt: scan je koelkast en zie meteen wat je ermee kan maken, volg videorecepten handsfree met je stem en zet ontbrekende ingrediënten met één klik op je boodschappenlijst — inclusief prijsschatting.

Gebouwd als bachelorproject met Laravel 13, Livewire 4 en Filament 5.

**🔗 Live: [hapklaar.net](https://hapklaar.net/)**

## Features

- **Koelkastscanner** — upload een foto van je koelkast; GPT-4o-mini herkent de ingrediënten, die daarna met fuzzy matching gekoppeld worden aan de ingrediëntendatabase.
- **"Wat kan ik maken?"** — recepten worden gerangschikt op hoeveel ingrediënten je al hebt, op basis van een scan of je opgeslagen voorraad.
- **Handsfree koken met spraak** — zeg *"Hey Hapklaar, volgende stap"* of *"Hey Hapklaar, wanneer gaat de ui erin?"* en de receptvideo springt naar het juiste moment.
- **Videorecepten** — streaming via Mux, met per stap een tijdstempel in de video.
- **Boodschappenlijst met prijzen** — ontbrekende ingrediënten toevoegen vanuit een recept; prijzen worden geschat via de Albert Heijn API.
- **Voorraadbeheer** — houd bij wat je in huis hebt; na het koken haal je de gebruikte ingrediënten met één klik van je voorraad.
- **Voedingswaarden** — macro's per recept, berekend uit USDA-data per ingrediënt.
- **Ontdekken & filteren** — op dieet, hoeveel afwas een recept geeft, sortering, of laat een willekeurig recept kiezen.
- **Reviews met foto's, favorieten en een profielpagina.**
- **Adminpaneel** (Filament) — beheer recepten, stappen, reviews, gebruikers en site-inhoud.
- **Installeerbaar als PWA** op gsm.

## Hoe de spraakbesturing werkt

Dit was technisch het meest uitdagende onderdeel:

1. **Voice Activity Detection** in de browser (`@ricky0123/vad-web`, ONNX) detecteert wanneer iemand praat, zodat enkel echte spraak verstuurd wordt — geen continue opname.
2. Het audiofragment gaat naar de backend en wordt getranscribeerd met **OpenAI Whisper**. De prompt bevat de receptnaam en ingrediënten, wat de herkenning van kookwoorden sterk verbetert.
3. Zonder het wake word *"Hapklaar"* wordt de input genegeerd.
4. Het commando wordt in volgorde van prioriteit geïnterpreteerd: pauze/afspelen → volgende/vorige stap → "stap 3" → vrije tekst.
5. Vrije tekst wordt gematcht met de stapbeschrijvingen via een eigen **TF-IDF-achtige scoring** met een eenvoudige Nederlandse stemmer en stopwoordenlijst, zodat *"wanneer gaat de ui erin"* bij de juiste stap uitkomt.

Zie [`app/Http/Controllers/VoiceController.php`](app/Http/Controllers/VoiceController.php) en [`resources/js/voice.js`](resources/js/voice.js).

## Tech stack

| Laag | Technologie |
|---|---|
| Backend | PHP 8.3, Laravel 13 |
| Frontend | Livewire 4, Alpine.js, Tailwind CSS 4, Vite |
| Admin | Filament 5 |
| AI | OpenAI Whisper (spraak), GPT-4o-mini (beeldherkenning) |
| Video | Mux |
| Externe API's | USDA FoodData Central, Albert Heijn |
| E-mail | Resend |
| Hosting | Combell |

---

Gemaakt door **Arne Van Tulden** — [GitHub](https://github.com/ArneVanTulden)

