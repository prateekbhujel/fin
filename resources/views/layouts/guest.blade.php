@php
    $guestExperienceData = [
        'companyName' => setting('app.company_name', 'Fin'),
        'companyEmail' => setting('app.company_email', 'info@example.com'),
        'companyPhone' => setting('app.company_phone', '+977-1-0000000'),
        'companyAddress' => setting('app.address', 'Kathmandu, Nepal'),
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.tsx'])
</head>
<body class="min-h-full bg-gray-950">
    <div class="grid min-h-screen lg:grid-cols-[1.1fr_0.9fr]">
        <div class="hidden p-8 lg:block xl:p-10">
            <div id="guest-experience-root" class="h-full">
                <div class="relative flex h-full flex-col justify-between overflow-hidden rounded-[2rem] border border-white/10 bg-[radial-gradient(circle_at_top_left,_rgba(111,168,255,0.26),_transparent_36%),radial-gradient(circle_at_bottom_right,_rgba(244,114,182,0.18),_transparent_32%),linear-gradient(145deg,#0f172a_0%,#14213d_52%,#111827_100%)] p-10 text-white shadow-[0_40px_120px_rgba(15,23,42,0.42)]">
                    <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:38px_38px] opacity-30"></div>

                    <div class="relative">
                        <div class="inline-flex rounded-full border border-white/15 bg-white/10 px-4 py-1.5 text-[11px] font-semibold uppercase tracking-[0.24em] text-sky-100">
                            Nepali Finance Desk
                        </div>
                        <h1 class="mt-8 max-w-2xl text-4xl font-semibold leading-tight">
                            Know what came in, what went out, and which receipt is still missing.
                        </h1>
                        <p class="mt-5 max-w-xl text-base leading-7 text-white/74">
                            Built for finance teams that still work across cash, bank slips, Excel imports, and office follow-ups.
                        </p>
                    </div>

                    <div class="relative grid gap-4 xl:grid-cols-2">
                        <div class="rounded-[1.75rem] border border-white/10 bg-white/10 p-6 backdrop-blur">
                            <div class="text-sm font-semibold text-white">Start with the work people actually do every day.</div>
                            <div class="mt-3 text-sm leading-6 text-white/70">
                                Record entries, attach documents, and keep reports ready for the questions that come before lunch.
                            </div>
                        </div>
                        <div class="rounded-[1.75rem] border border-white/10 bg-white/10 p-6 backdrop-blur">
                            <div class="text-sm font-semibold text-white">TailAdmin feel, React + TypeScript frontend.</div>
                            <div class="mt-3 text-sm leading-6 text-white/70">
                                A calmer admin workspace with B.S. date support and shared-hosting-friendly Laravel underneath.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script id="guest-experience-data" type="application/json">@json($guestExperienceData)</script>

        <div class="flex items-center justify-center bg-gray-50 px-6 py-10 dark:bg-gray-950">
            <div class="w-full max-w-md rounded-[2rem] border border-gray-200 bg-white p-8 shadow-xl dark:border-gray-800 dark:bg-gray-900">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
