<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';

defineOptions({ layout: undefined });
</script>

<template>
    <Head title="Privacyverklaring" />

    <div class="mx-auto max-w-3xl px-6 py-12 text-sm leading-relaxed">
        <Heading
            title="Privacyverklaring"
            description="Informatie over de verwerking van uw persoonsgegevens (AVG Art. 13)"
        />

        <div class="mt-8 space-y-8">
            <section>
                <h2 class="mb-2 font-semibold">
                    1. Wie is de verwerkingsverantwoordelijke?
                </h2>
                <p>
                    De arbodienst die via dit platform uw verzuimbegeleiding
                    verzorgt, is de verwerkingsverantwoordelijke in de zin van
                    de Algemene Verordening Gegevensbescherming (AVG). De exacte
                    naam en contactgegevens staan vermeld in uw
                    dienstverleningsovereenkomst (DVO).
                </p>
            </section>

            <section>
                <h2 class="mb-2 font-semibold">
                    2. Welke gegevens verwerken wij?
                </h2>
                <ul
                    class="list-inside list-disc space-y-1 text-muted-foreground"
                >
                    <li>
                        Naam, functietitel en contactgegevens van medewerkers en
                        contactpersonen
                    </li>
                    <li>
                        Organisatiestructuur en dienstverbanden (afdeling,
                        contract)
                    </li>
                    <li>
                        Verzuimgegevens: ziekteperioden, verwachte hersteldatum,
                        re-integratietraject
                    </li>
                    <li>
                        Medische gegevens (alleen toegankelijk voor de
                        bedrijfsarts — niet zichtbaar voor u als werkgever)
                    </li>
                    <li>
                        Inloggegevens en auditlogboeken (toegangsregistratie
                        conform NEN 7513)
                    </li>
                </ul>
            </section>

            <section>
                <h2 class="mb-2 font-semibold">
                    3. Grondslagen voor verwerking
                </h2>
                <p>
                    De verwerking berust op de volgende grondslagen (AVG Art. 6
                    en Art. 9):
                </p>
                <ul
                    class="mt-2 list-inside list-disc space-y-1 text-muted-foreground"
                >
                    <li>
                        <strong>Wettelijke verplichting</strong> — Wet
                        verbetering poortwachter, Arbowet, WGBO
                    </li>
                    <li>
                        <strong>Uitvoering van een overeenkomst</strong> — DVO
                        tussen uw organisatie en de arbodienst
                    </li>
                    <li>
                        <strong>Gerechtvaardigd belang</strong> —
                        fraudepreventie, beveiliging en continuïteit van
                        dienstverlening
                    </li>
                    <li>
                        <strong>Toestemming</strong> — waar van toepassing (u
                        kunt toestemming te allen tijde intrekken)
                    </li>
                </ul>
            </section>

            <section>
                <h2 class="mb-2 font-semibold">4. Bewaartermijnen</h2>
                <p>
                    Verzuimdossiers worden bewaard conform de wettelijke
                    termijnen:
                </p>
                <ul
                    class="mt-2 list-inside list-disc space-y-1 text-muted-foreground"
                >
                    <li>
                        Algemene verzuimgegevens: 2 jaar na afsluiting van het
                        dossier (Wet poortwachter)
                    </li>
                    <li>
                        Medische dossiers (bedrijfsarts): 20 jaar na laatste
                        behandelmoment (WGBO Art. 454)
                    </li>
                    <li>Auditlogboeken: 5 jaar (NEN 7513)</li>
                </ul>
            </section>

            <section>
                <h2 class="mb-2 font-semibold">5. Doorgifte aan derden</h2>
                <p>
                    Gegevens worden niet aan derden verstrekt tenzij dit
                    wettelijk verplicht is (bijv. UWV in het kader van de Wet
                    poortwachter) of u daarvoor toestemming heeft gegeven.
                    Verwerkers (zoals de aanbieder van dit platform) zijn
                    contractueel gebonden via een verwerkersovereenkomst.
                </p>
            </section>

            <section>
                <h2 class="mb-2 font-semibold">
                    6. Uw rechten (AVG Art. 15–22)
                </h2>
                <p>U heeft de volgende rechten:</p>
                <ul
                    class="mt-2 list-inside list-disc space-y-1 text-muted-foreground"
                >
                    <li>
                        <strong>Inzage</strong> (Art. 15) — u kunt een overzicht
                        opvragen van uw verwerkte gegevens
                    </li>
                    <li>
                        <strong>Rectificatie</strong> (Art. 16) — onjuiste
                        gegevens laten corrigeren
                    </li>
                    <li>
                        <strong>Wissing</strong> (Art. 17) — verwijdering van
                        gegevens wanneer de wettelijke bewaartermijn is
                        verstreken
                    </li>
                    <li>
                        <strong>Beperking</strong> (Art. 18) — tijdelijke
                        beperking van de verwerking
                    </li>
                    <li>
                        <strong>Overdraagbaarheid</strong> (Art. 20) — gegevens
                        in machineleesbaar formaat opvragen
                    </li>
                    <li>
                        <strong>Bezwaar</strong> (Art. 21) — bezwaar maken tegen
                        verwerking op grond van gerechtvaardigd belang
                    </li>
                </ul>
                <p class="mt-2">
                    Verzoeken kunt u richten aan de functionaris voor
                    gegevensbescherming (FG) van de arbodienst. U heeft ook het
                    recht een klacht in te dienen bij de
                    <a
                        href="https://www.autoriteitpersoonsgegevens.nl"
                        target="_blank"
                        rel="noopener"
                        class="underline"
                    >
                        Autoriteit Persoonsgegevens </a
                    >.
                </p>
            </section>

            <section>
                <h2 class="mb-2 font-semibold">7. Beveiliging</h2>
                <p>
                    Het platform past passende technische en organisatorische
                    maatregelen toe conform NEN 7510. Dit omvat versleuteling
                    van medische gegevens, tweefactorauthenticatie en een
                    tamper-evident auditlogboek (NEN 7513).
                </p>
            </section>

            <p class="mt-8 text-xs text-muted-foreground">
                Laatste update: juli 2026
            </p>
        </div>
    </div>
</template>
