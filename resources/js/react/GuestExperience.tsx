import { ConfigProvider, Tag, Timeline, Tooltip } from 'antd';

type GuestExperienceProps = {
    companyName: string;
    companyEmail: string;
    companyPhone: string;
    companyAddress: string;
};

const checklist = [
    {
        title: 'Record the day without slowing the desk down',
        description: 'Cash, bank, wallet, and cheque entries stay in one place instead of drifting across paper and chat.',
    },
    {
        title: 'Keep documents where the numbers live',
        description: 'Receipts and supporting files can travel with the transaction, not in a separate folder hunt.',
    },
    {
        title: 'Answer management questions faster',
        description: 'Monthly totals, category spend, and B.S. date-aware views are ready when the office asks.',
    },
];

export function GuestExperience({
    companyName,
    companyEmail,
    companyPhone,
    companyAddress,
}: GuestExperienceProps) {
    return (
        <ConfigProvider
            theme={{
                token: {
                    colorPrimary: '#405cf5',
                    borderRadius: 18,
                    fontFamily: 'Outfit, sans-serif',
                },
            }}
        >
            <div className="relative flex h-full flex-col justify-between overflow-hidden rounded-[2rem] border border-white/10 bg-[radial-gradient(circle_at_top_left,_rgba(111,168,255,0.26),_transparent_36%),radial-gradient(circle_at_bottom_right,_rgba(244,114,182,0.18),_transparent_32%),linear-gradient(145deg,#0f172a_0%,#14213d_52%,#111827_100%)] p-8 text-white shadow-[0_40px_120px_rgba(15,23,42,0.42)]">
                <div className="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:38px_38px] opacity-30" />

                <div className="relative">
                    <div className="flex flex-wrap items-center gap-3">
                        <Tag color="blue" className="!mr-0 !rounded-full !border-0 !px-4 !py-1.5 !text-[11px] !font-semibold !uppercase !tracking-[0.24em]">
                            Nepali Finance Desk
                        </Tag>
                        <div className="text-sm text-white/60">{companyName}</div>
                    </div>

                    <h1 className="mt-8 max-w-2xl text-4xl font-semibold leading-tight text-white xl:text-[2.9rem]">
                        Know what came in, what went out, and which receipt is still missing.
                    </h1>

                    <p className="mt-5 max-w-xl text-base leading-7 text-slate-200/82">
                        Built for finance teams that still work across cash, bank slips, Excel imports, and office follow-ups.
                        The goal is simple: fewer loose ends at the end of the day.
                    </p>
                </div>

                <div className="relative mt-10 grid gap-5 xl:grid-cols-[1.2fr_0.8fr]">
                    <div className="rounded-[1.75rem] border border-white/12 bg-white/10 p-6 backdrop-blur-md">
                        <div className="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <div className="text-sm font-medium text-white/68">A calmer routine for the office desk</div>
                                <div className="mt-2 text-2xl font-semibold text-white">Start with the work people actually do every day.</div>
                            </div>
                            <Tooltip title="Shared-hosting-friendly Laravel setup with React + TypeScript on the frontend.">
                                <Tag color="cyan" className="!mr-0 !rounded-full !border-0 !px-4 !py-2 !font-medium">
                                    TailAdmin + Ant Design
                                </Tag>
                            </Tooltip>
                        </div>

                        <div className="mt-6">
                            <Timeline
                                items={checklist.map((item) => ({
                                    color: '#6ea8ff',
                                    children: (
                                        <div className="pb-3">
                                            <div className="text-sm font-semibold text-white">{item.title}</div>
                                            <div className="mt-1 text-sm leading-6 text-white/68">{item.description}</div>
                                        </div>
                                    ),
                                }))}
                            />
                        </div>
                    </div>

                    <div className="space-y-4">
                        <div className="rounded-[1.75rem] border border-white/12 bg-slate-950/35 p-5 backdrop-blur-md">
                            <div className="text-xs font-semibold uppercase tracking-[0.24em] text-sky-200/80">Daily Notes</div>
                            <div className="mt-4 space-y-3 text-sm text-white/74">
                                <div className="rounded-2xl border border-white/10 bg-white/6 p-4">
                                    Yesterday’s entries should be easy to review before the phone starts ringing.
                                </div>
                                <div className="rounded-2xl border border-white/10 bg-white/6 p-4">
                                    Management usually wants a number, a date, and the supporting slip. Keep all three together.
                                </div>
                            </div>
                        </div>

                        <div className="rounded-[1.75rem] border border-white/12 bg-white/10 p-5 backdrop-blur-md">
                            <div className="text-xs font-semibold uppercase tracking-[0.24em] text-white/56">Contact Setup</div>
                            <div className="mt-4 space-y-3 text-sm text-white/80">
                                <div>{companyEmail}</div>
                                <div>{companyPhone}</div>
                                <div>{companyAddress}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </ConfigProvider>
    );
}
