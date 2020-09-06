import React from 'react'
import { Grid, IconButton, Typography, lighten } from '@material-ui/core'
import ReactTooltip from 'react-tooltip'
import { ComposableMap, Geographies, Geography } from 'react-simple-maps'
import CloseIcon from '@material-ui/icons/Close'
import Chart from 'react-apexcharts'
import axios from 'axios'

const API_DOMAIN = 'http://18.222.104.191/'

const ToolTipCountry = ({ division, party, seat_count }) => {
    return (
        <div>
            <Typography variant='subtitle1'>{division}</Typography>
            {
                (party === '') ? <Typography variant='subtitle2'>Loading...</Typography> :
                    <div>
                        <Typography variant='subtitle2'>{`Dominant Party: ${party}`}</Typography>
                        <Typography variant='subtitle2'>{`Total Seats: ${seat_count}`}</Typography>
                    </div>
            }
        </div>
    )
}

const ToolTipDivision = ({ division, total_vote, party, constituency }) => {
    return (
        <div>
            <Typography variant='subtitle1'>{division}</Typography>
            {
                (party === '') ? <Typography variant='subtitle2'>Loading...</Typography> :
                    <div>
                        <Typography variant='subtitle2'>{`Party: ${party}`}</Typography>
                        <Typography variant='subtitle2'>{`Constituency: ${constituency}`}</Typography>
                        <Typography variant='subtitle2'>{`Total Vote: ${total_vote}`}</Typography>
                    </div>
            }
        </div>
    )
}

const Hluttaw = (props) => {
    const { name } = props

    const [scale, setScale] = React.useState(3900)
    const [rotate, setRotate] = React.useState([-96.7, -19.2, 0])
    const [content, setContent] = React.useState("")
    const [type, setType] = React.useState('myanmar')
    const [isDivision, setIsDivision] = React.useState(false)
    const [showResult, setShowResult] = React.useState(false)
    const [divisionName, setDivisionName] = React.useState('')

    const [dominantParties, setDominantParties] = React.useState({})
    const [country, setCountry] = React.useState([])
    const [country2015, setCountry2015] = React.useState([])
    const [voteData, setVoteData] = React.useState([])
    const [gender, setGender] = React.useState([])

    const [divParties, setDivParties] = React.useState({})
    const [divTotal, setDivTotal] = React.useState([])
    const [division, setDivision] = React.useState([])
    const [divGender, setDivGender] = React.useState([])
    const [divVote, setDivVote] = React.useState([])

    const [consitution, setConsitution] = React.useState([])
    const [consitutionVote, setConsitutionVote] = React.useState([])
    const [candidate, setCandidate] = React.useState(null)

    React.useEffect(() => {
        axios.get(API_DOMAIN + 'api/v1/nationalityhouse_country/2015')
            .then(res => {
                const parties = {}
                res.data.forEach(party => {
                    parties[party.pcode] = party
                });
                setDominantParties(parties)
            })
    }, [])

    React.useEffect(() => {
        axios.get(API_DOMAIN + 'api/v1/nationalityhouse_compare/country')
            .then(res => {
                setCountry(res.data.sort(function (a, b) { return ((a['2015_seats'] - a['2010_seats']) < (b['2015_seats'] - b['2010_seats'])) ? 1 : (((b['2015_seats'] - b['2010_seats']) < (a['2015_seats'] - a['2010_seats'])) ? -1 : 0) }))
            })
    }, [])

    React.useEffect(() => {
        axios.get(API_DOMAIN + 'api/v1/nationalityhouse_total/2015')
            .then(res => {
                const sorted = res.data.sort(function (a, b) { return (a.total_votes < b.total_votes) ? 1 : ((b.total_votes < a.total_votes) ? -1 : 0) })
                const others = {
                    party_name: 'အခြား', party_color: '#454545', total_votes: sorted.slice(3).map(s => s.total_votes).reduce(function (a, b) {
                        return a + b
                    }, 0)
                }
                const all = sorted.slice(0, 3)
                all.push(others)
                setCountry2015(all)
            })
    }, [])

    React.useEffect(() => {
        axios.get(API_DOMAIN + 'api/v1/census/country')
            .then(res => {
                setVoteData([res.data['total_votes'], res.data.absent_voters])
            })
    }, [])

    React.useEffect(() => {
        axios.get(API_DOMAIN + 'api/v1/nationalityhouse_total/2015?type=gender')
            .then(res => {
                setGender([{ name: 'Male', data: [res.data[0].male.total_candidate, res.data[0].male.elected_candidate] }, { name: 'Female', data: [res.data[1].female.total_candidate, res.data[1].female.elected_candidate] }])
            })
    }, [])


    const fetchDivision = (division, id) => {
        axios.get(API_DOMAIN + `api/v1/nationalityhouse_region/${division}/2015`)
            .then(res => {
                const parties = {}
                res.data.forEach(party => {
                    parties[party.pcode] = party
                });
                setDivParties(parties)
            })
        axios.get(API_DOMAIN + `api/v1/nationalityhouse_region/${division}/2015?type=total`)
            .then(res => {
                setDivTotal(res.data)
            })

        axios.get(API_DOMAIN + `api/v1/nationalityhouse_compare/region?region=${division}`)
            .then(res => {
                setDivision(res.data.sort(function (a, b) { return ((a['2015_seats'] - a['2010_seats']) < (b['2015_seats'] - b['2010_seats'])) ? 1 : (((b['2015_seats'] - b['2010_seats']) < (a['2015_seats'] - a['2010_seats'])) ? -1 : 0) }))
            })

        axios.get(API_DOMAIN + `api/v1/nationalityhouse_candidates/${division}/2015`)
            .then(res => {
                setDivGender([{ name: 'Male', data: [res.data[0].male.total_candidate, res.data[0].male.elected_candidate] }, { name: 'Female', data: [res.data[1].female.total_candidate, res.data[1].female.elected_candidate] }])
            })

        axios.get(API_DOMAIN + `api/v1/census/region?region_id=${division}&area_id=${id}&year=2015`)
            .then(res => {
                setDivVote([res.data['total_votes'], res.data.absent_voters])
            })
    }

    const fetchConstitution = (seat) => {
        axios.get(API_DOMAIN + `api/v1/nationalityhouse_seat/${seat}`)
            .then(res => {
                const candidates = res.data.data.sort(function (a, b) { return (a.total_vote < b.total_vote) ? 1 : ((b.total_vote < a.total_vote) ? -1 : 0) })
                setCandidate(candidates[0])
                setConsitution(candidates.slice(0, candidates.length > 5 ? 5 : candidates.length))
            })

        axios.get(API_DOMAIN + `api/v1/census/constitution?house_id=${seat}`)
            .then(res => {
                setConsitutionVote([res.data['total_votes'], res.data.absent_voters])
            })
    }

    const cleanDiv = () => {
        setDivTotal([])
        setDivisionName('')
        setDivParties({})
        setDivision([])
        setConsitution([])
        setDivGender([])
        setConsitutionVote([])
        setCandidate(null)
    }

    return (
        <div>
            <Grid container spacing={2} direction='row' width='100%' >
                <Grid md={12} sm={12} item>
                    <Typography style={{
                        fontSize: 20,
                        fontWeight: 'bold',
                    }}>
                        {`${name}${isDivision ? ` - ${divisionName}` : ''}`}
                    </Typography>
                </Grid>
                <Grid md={4} sm={4} item>
                    <Grid item md={12} sm={12}>
                        <div style={{ position: 'relative', background: '#ffffff', borderRadius: 15, zIndex: 1 }}>
                            {isDivision &&
                                <div style={{ position: 'absolute', padding: 10 }}>
                                    <IconButton aria-label='close' size='small' style={{ backgroundColor: '#fafafa' }} onClick={() => {
                                        setType('myanmar')
                                        setRotate([-96.7, -19.2, 0])
                                        setScale(3900)
                                        setIsDivision(false)
                                        setShowResult(false)
                                        cleanDiv()
                                    }}>
                                        <CloseIcon />
                                    </IconButton>
                                </div>}
                            <div style={{ position: 'absolute', padding: 10, bottom: 10 }}>
                                {
                                    Object.values(isDivision ? divParties : dominantParties).map(value => { return value }).reduce((unique, o) => {
                                        if (!unique.some(obj => obj.party_name === o.party_name)) {
                                            unique.push(o);
                                        }
                                        return unique
                                    }, []).map(value => {
                                        return (
                                            <div id={value.party_name}>
                                                <div style={{ margin: '0px 3px', display: 'inline-flex', alignItems: 'baseline', fontSize: '13px', fontFamily: 'Helvetica, Arial', fontWeight: 350, userSelect: 'none' }}>
                                                    <div style={{ width: '11px', height: '11px', backgroundColor: value.party_color, margin: '2px', borderRadius: '2px' }} />
                                                    {value.party_name}
                                                </div>
                                            </div>
                                        )
                                    })

                                }
                            </div>
                            <ComposableMap data-tip='' projectionConfig={{
                                scale: scale,
                                rotate: rotate,
                            }} style={{ width: '100%', height: '100vh', }} projection='geoEquirectangular'>
                                <Geographies geography=
                                    {`https://raw.githubusercontent.com/dscyrescotti/map/master/${type}.json`}
                                >
                                    {({ geographies }) =>
                                        geographies.map((geo) => {
                                            const color = isDivision ? (divParties[geo.properties.PCODE] !== undefined ? divParties[geo.properties.PCODE].party_color : '#000000') : dominantParties[geo.properties.PCODE] !== undefined ? dominantParties[geo.properties.PCODE].party_color : '#000000'
                                            return (
                                                <Geography
                                                    key={geo.rsmKey}
                                                    geography={geo}
                                                    stroke='#EAEAEC'
                                                    onMouseEnter={() => {
                                                        const { PCODE, NAME } = geo.properties
                                                        if (!isDivision) {
                                                            const { total_seats, party_name } = dominantParties[PCODE] ?? { total_seats: '', party_name: '' }
                                                            setContent(<ToolTipCountry division={NAME} party={party_name} seat_count={total_seats} />)
                                                        } else {
                                                            if (divParties[PCODE] === undefined) {
                                                                return
                                                            }
                                                            const { total_vote, party_name, nationalityhouse_name } = divParties[PCODE]
                                                            setContent(<ToolTipDivision division={NAME} party={party_name} total_vote={total_vote} constituency={nationalityhouse_name} />)
                                                        }
                                                    }}
                                                    onMouseLeave={() => {
                                                        setContent('')
                                                    }}
                                                    onClick={() => {
                                                        const { PCODE, NAME } = geo.properties

                                                        if (!isDivision) {
                                                            if (dominantParties[PCODE] === undefined) {
                                                                return
                                                            }
                                                            if (PCODE !== 'MMR013' && PCODE !== 'MMR012') {
                                                                setType('')
                                                            }
                                                            if (PCODE === 'MMR013') {
                                                                setType('Yangon_AmyothaHluttaw')
                                                                setRotate([-96.22, -17.05, 0])
                                                                setScale(37000)
                                                            }
                                                            if (PCODE === 'MMR012') {
                                                                setType('Rakhine_AmyothaHluttaw')
                                                                setRotate([-93.55, -19.5, 0])
                                                                setScale(17000)
                                                            }
                                                            setIsDivision(true)
                                                            setDivisionName(NAME)
                                                            fetchDivision(NAME, dominantParties[PCODE].area_id)
                                                        } else {
                                                            if (divParties[PCODE] !== undefined) {
                                                                fetchConstitution(divParties[PCODE].nationalityhouse_id)
                                                                setShowResult(true)
                                                            }
                                                        }
                                                    }}
                                                    style={{
                                                        default: {
                                                            fill: color,
                                                            outline: 'none',
                                                            cursor: 'pointer'
                                                        },
                                                        hover: {
                                                            fill: lighten(color, 0.3),
                                                            outline: 'none',
                                                            cursor: 'pointer'
                                                        },
                                                        pressed: {
                                                            fill: lighten(color, 0.3),
                                                            outline: 'none',
                                                            cursor: 'pointer'
                                                        },
                                                    }}
                                                />
                                            )
                                        })

                                    }
                                </Geographies>
                            </ComposableMap>
                        </div>
                        <ReactTooltip>{content}</ReactTooltip>
                    </Grid>
                </Grid>

                {!showResult &&
                    <Grid item md={8} sm={8} direction='column' spacing={2} justify='space-between' >
                        <Grid item md={12} sm={12}>
                            <div style={{ backgroundColor: '#ffffff', padding: 10, borderRadius: 15 }}>
                                <Chart
                                    type='bar'
                                    options={
                                        {
                                            chart: {
                                                toolbar: {
                                                    show: false
                                                },
                                                zoom: {
                                                    enabled: false
                                                }
                                            },
                                            dataLabels: {
                                                enabled: false,
                                            },
                                            noData: {
                                                text: 'Loading...'
                                            },
                                            plotOptions: {
                                                bar: {
                                                    columnWidth: '60%',
                                                    distributed: true
                                                }
                                            },
                                            stroke: {
                                                show: true,
                                                width: 2,
                                                colors: ['transparent']
                                            },

                                            colors: isDivision ? division.map(d => d['party_color'].trim()) : country.map(d => d['party_color'].trim()),
                                            xaxis: {
                                                type: 'category',
                                                categories: isDivision ? division.map(d => d.party_name) : country.map(d => d.party_name),
                                                labels: {
                                                    show: false,
                                                }
                                            },
                                            title: {
                                                text: 'Seat changes compared with 2010',
                                                align: 'left',
                                                style: {
                                                    fontSize: '20px'
                                                }
                                            },
                                            legend: {
                                                show: true,
                                                position: 'right',
                                                horizontalAlign: 'left',
                                                onItemClick: {
                                                    toggleDataSeries: false
                                                },
                                                onItemHover: {
                                                    highlightDataSeries: false
                                                },
                                            },
                                            fill: {
                                                opacity: 1
                                            }
                                        }}
                                    series={
                                        [
                                            {
                                                name: 'Seat changes',
                                                data: isDivision ? division.map(d => d['difference']) : country.map(d => d['difference'])
                                            },
                                        ]
                                    }
                                    height={'325'}
                                    width={'100%'}
                                />
                            </div>
                        </Grid>
                        <br />
                        <Grid item md={12} sm={12}>
                            <Grid container justify='flex-start' spacing={2}>
                                <Grid item md={4} sm={4}>
                                    <div style={{ backgroundColor: '#ffffff', padding: 10, borderRadius: 15, }}>
                                        <Chart
                                            type='donut'
                                            options={
                                                {
                                                    chart: {
                                                        toolbar: {
                                                            show: false
                                                        },
                                                        zoom: {
                                                            enabled: false
                                                        }
                                                    },
                                                    dataLabels: {
                                                        enabled: true,
                                                        style: {
                                                            fontSize: '10px',
                                                        }
                                                    },
                                                    stroke: {
                                                        show: false
                                                    },
                                                    labels: isDivision ? divTotal.map(party => party.party_name) : country2015.map(party => party.party_name),
                                                    colors: isDivision ? divTotal.map(party => party.party_color) : country2015.map(party => party['party_color'].trim()),
                                                    title: {
                                                        text: 'Seat Percentages',
                                                        align: 'left',
                                                        style: {
                                                            fontSize: '20px'
                                                        }
                                                    },
                                                    noData: {
                                                        text: 'Loading...'
                                                    },

                                                    plotOptions: {
                                                        pie: {
                                                            donut: {
                                                                labels: {
                                                                    show: true,
                                                                    total: {
                                                                        show: true,
                                                                        showAlways: true,
                                                                        formatter: function (w) {
                                                                            return w.globals.seriesTotals.reduce((a, b) => {
                                                                                return a + b
                                                                            }, 0)
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    },
                                                    legend: {
                                                        show: true,
                                                        position: 'bottom',
                                                        horizontalAlign: 'left',
                                                        onItemClick: {
                                                            toggleDataSeries: false
                                                        },
                                                        onItemHover: {
                                                            highlightDataSeries: false
                                                        },
                                                        fontSize: '9.5px'
                                                    },
                                                }
                                            }
                                            series={
                                                isDivision ? divTotal.map(party => party.total_votes) : country2015.map(party => party.total_votes)
                                            }
                                            width='100%'
                                            height='290'
                                        />
                                    </div>
                                </Grid>
                                <Grid item md={4} sm={4}>
                                    <div style={{ backgroundColor: '#ffffff', padding: 10, borderRadius: 15 }}>
                                        <Chart
                                            type='donut'
                                            options={
                                                {
                                                    chart: {
                                                        toolbar: {
                                                            show: false
                                                        },
                                                        zoom: {
                                                            enabled: false
                                                        }
                                                    },
                                                    dataLabels: {
                                                        enabled: true,
                                                        style: {
                                                            fontSize: '10px',
                                                        }
                                                    },
                                                    stroke: {
                                                        show: false
                                                    },
                                                    noData: {
                                                        text: 'Loading...'
                                                    },
                                                    colors: ["#184a45", "#b0b8b4"],
                                                    labels: ['Valid vote', 'No vote / Spoilt vote'],
                                                    plotOptions: {
                                                        pie: {
                                                            donut: {
                                                                labels: {
                                                                    show: true,
                                                                    total: {
                                                                        show: true,
                                                                        showAlways: true,
                                                                        formatter: function (w) {
                                                                            return w.globals.seriesTotals.reduce((a, b) => {
                                                                                return a + b
                                                                            }, 0)
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    },
                                                    title: {
                                                        text: 'Voting Validity',
                                                        align: 'left',
                                                        style: {
                                                            fontSize: '20px'
                                                        }
                                                    },
                                                    legend: {
                                                        show: true,
                                                        position: 'bottom',
                                                        horizontalAlign: 'left',
                                                        onItemClick: {
                                                            toggleDataSeries: false
                                                        },
                                                        onItemHover: {
                                                            highlightDataSeries: false
                                                        },
                                                    },
                                                }
                                            }
                                            series={isDivision ? divVote : voteData}
                                            width='100%'
                                            height='290'
                                        />
                                    </div>
                                </Grid>
                                <Grid item md={4} sm={4}>
                                    <div style={{ backgroundColor: '#ffffff', padding: 10, borderRadius: 15 }}>
                                        <Chart
                                            type='bar'
                                            options={
                                                {
                                                    chart: {
                                                        toolbar: {
                                                            show: false
                                                        },
                                                        zoom: {
                                                            enabled: false
                                                        },
                                                        stacked: true,
                                                        stackType: '100%'
                                                    },
                                                    dataLabels: {
                                                        enabled: true,
                                                        style: {
                                                            fontSize: '10px',
                                                        }
                                                    },
                                                    stroke: {
                                                        show: false
                                                    },
                                                    noData: {
                                                        text: 'Loading...'
                                                    },
                                                    yaxis: {
                                                        labels: {
                                                            show: true,
                                                            floating: true,
                                                        }
                                                    },
                                                    colors: ["#4a8fff", "#ff87fb"],
                                                    labels: ['Candidates', 'Elected'],
                                                    title: {
                                                        text: 'Gender Influence',
                                                        align: 'left',
                                                        style: {
                                                            fontSize: '20px'
                                                        }
                                                    },
                                                    plotOptions: {
                                                        bar: {
                                                            horizontal: true,
                                                        },
                                                    },
                                                    legend: {
                                                        show: true,
                                                        position: 'bottom',
                                                        horizontalAlign: 'left',
                                                        onItemClick: {
                                                            toggleDataSeries: false
                                                        },
                                                        onItemHover: {
                                                            highlightDataSeries: false
                                                        },
                                                    },
                                                }
                                            }
                                            series={isDivision ? divGender : gender}
                                            width='100%'
                                            height='290'
                                        />
                                    </div>
                                </Grid>
                            </Grid>
                        </Grid>
                    </Grid>
                }
                {showResult &&
                    <Grid item sm={8} md={8} direction='column'>
                        <Grid item sm={12} direction='column' >
                            {candidate !== null && (
                                <div style={{ backgroundColor: '#ffffff', padding: 10, borderRadius: 15 }}>
                                    <div>
                                        <IconButton aria-label='close' size='small' style={{ backgroundColor: '#fafafa' }} onClick={() => {
                                            setShowResult(false)
                                            setCandidate(null)
                                            setConsitution([])
                                            setConsitutionVote([])

                                        }}>
                                            <CloseIcon />
                                        </IconButton>
                                    </div>

                                    <div style={{ padding: 5 }}>
                                        <Typography variant='h5' style={{ fontWeight: 'bold' }}>
                                            {'Elected candidate for ' + candidate.seat_name}
                                        </Typography>
                                    </div>
                                    <div style={{ padding: 5 }}>
                                        <div>
                                            <img alt='party_logo' style={{ width: 200, height: 100, }} src={candidate.party_logo} />
                                            <Typography variant='h6' style={{ fontWeight: 'bold' }}>{candidate.candidate_name}</Typography>
                                            <Typography variant='h6' style={{ fontWeight: 'bold' }}>{candidate.party_name}</Typography>

                                            <Typography variant='h6' style={{ fontWeight: 'bold' }}>{'Total Votes: ' + candidate.total_vote.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' votes'}</Typography>
                                        </div>
                                    </div>
                                </div>
                            )}
                            {candidate === null && (<div style={{ height: 150, backgroundColor: '#ffffff', padding: 10, borderRadius: 15 }} />)}
                        </Grid>
                        <br />
                        <Grid container md={12} sm={12} spacing={2}>
                            <Grid item sm={6} md={6}>
                                <div style={{ backgroundColor: '#ffffff', padding: 10, borderRadius: 15 }}>
                                    <Chart
                                        type='bar'
                                        options={
                                            {
                                                chart: {
                                                    toolbar: {
                                                        show: false
                                                    },
                                                    zoom: {
                                                        enabled: false
                                                    }
                                                },
                                                dataLabels: {
                                                    enabled: false
                                                },
                                                stroke: {
                                                    show: true,
                                                    width: 2,
                                                    colors: ['transparent']
                                                },
                                                plotOptions: {
                                                    bar: {
                                                        columnWidth: '45%',
                                                        distributed: true
                                                    }
                                                },
                                                colors: consitution.map(c => c.party_color.trim()),
                                                xaxis: {
                                                    type: 'category',
                                                    categories: consitution.map(c => c.party_name),
                                                    labels: {
                                                        show: false,
                                                    }
                                                },
                                                title: {
                                                    text: 'Top 5 Voted Parties',
                                                    align: 'left',
                                                    style: {
                                                        fontSize: '20px'
                                                    }
                                                },
                                                noData: {
                                                    text: 'Loading...'
                                                },
                                                legend: {
                                                    show: true,
                                                    position: 'bottom',
                                                    horizontalAlign: 'left',
                                                    onItemClick: {
                                                        toggleDataSeries: false
                                                    },
                                                    onItemHover: {
                                                        highlightDataSeries: false
                                                    },
                                                },
                                                fill: {
                                                    opacity: 1
                                                }
                                            }}
                                        series={
                                            [{
                                                name: '2015',
                                                data: consitution.map(c => c.total_vote)
                                            },
                                            ]
                                        }
                                        height={'350'}
                                        width={'100%'}
                                    />
                                </div>
                            </Grid>
                            <Grid item md={6} sm={6}>
                                <div style={{ backgroundColor: '#ffffff', padding: 10, borderRadius: 15 }}>
                                    <Chart
                                        type='donut'
                                        options={
                                            {
                                                chart: {
                                                    toolbar: {
                                                        show: false
                                                    },
                                                    zoom: {
                                                        enabled: false
                                                    }
                                                },
                                                dataLabels: {
                                                    enabled: true,
                                                    style: {
                                                        fontSize: '10px',
                                                    }
                                                },
                                                stroke: {
                                                    show: false
                                                },
                                                noData: {
                                                    text: 'Loading...'
                                                },
                                                colors: ["#184a45", "#b0b8b4"],
                                                labels: ['Valid vote', 'No vote / Spoilt vote'],
                                                plotOptions: {
                                                    pie: {
                                                        donut: {
                                                            labels: {
                                                                show: true,
                                                                total: {
                                                                    show: true,
                                                                    showAlways: true,
                                                                    formatter: function (w) {
                                                                        return w.globals.seriesTotals.reduce((a, b) => {
                                                                            return a + b
                                                                        }, 0)
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                },
                                                title: {
                                                    text: 'Voting Validity',
                                                    align: 'left',
                                                    style: {
                                                        fontSize: '20px'
                                                    }
                                                },
                                                legend: {
                                                    show: true,
                                                    position: 'bottom',
                                                    horizontalAlign: 'left',
                                                    onItemClick: {
                                                        toggleDataSeries: false
                                                    },
                                                    onItemHover: {
                                                        highlightDataSeries: false
                                                    },
                                                },
                                            }
                                        }
                                        series={consitutionVote}
                                        width='100%'
                                        height='290'
                                    />
                                </div>
                            </Grid>
                        </Grid>
                    </Grid>
                }
            </Grid>
        </div>
    )
}

export default Hluttaw