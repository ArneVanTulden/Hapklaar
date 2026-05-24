import { MicVAD, utils } from '@ricky0123/vad-web'

let vadInstance = null

window.VAD = {
    async toggle({ recipeId, csrfToken, onMatch, onStatus, onToggle }) {
        if (vadInstance) {
            vadInstance.destroy()
            vadInstance = null
            onToggle(false)
            onStatus('')
            return
        }

        onStatus('Opstarten...')

        try {
            vadInstance = await MicVAD.new({
                workletURL:  '/vad/vad.worklet.bundle.min.js',
                modelURL:    '/vad/silero_vad_v5.onnx',
                ortConfig(ort) {
                    ort.env.wasm.wasmPaths = '/vad/'
                },
                onSpeechStart() {
                    onStatus('Luisteren...')
                },
                async onSpeechEnd(audio) {
                    onStatus('Verwerken...')

                    try {
                        const wav  = utils.encodeWAV(audio)
                        const blob = new Blob([wav], { type: 'audio/wav' })
                        const form = new FormData()
                        form.append('audio', blob, 'speech.wav')
                        form.append('_token', csrfToken)

                        const res  = await fetch(`/recepten/${recipeId}/voice`, { method: 'POST', body: form })
                        const data = await res.json()

                        if (data.wakeword_found) {
                            if (data.timestamp !== null && data.timestamp !== undefined) {
                                onMatch(data.timestamp, data.step)
                                onStatus(data.step ? `Stap ${data.step} gevonden` : 'Gevonden')
                            } else {
                                onStatus(data.command ? `"${data.command}" — niet gevonden` : 'Niet begrepen')
                            }
                            setTimeout(() => onStatus('Zeg "Hey Hapklaar..."'), 3000)
                        } else {
                            onStatus('Zeg "Hey Hapklaar..."')
                        }
                    } catch {
                        onStatus('Fout opgetreden')
                        setTimeout(() => onStatus('Zeg "Hey Hapklaar..."'), 2000)
                    }
                },
            })

            vadInstance.start()
            onToggle(true)
            onStatus('Zeg "Hey Hapklaar..."')
        } catch {
            onStatus('Microfoon toegang geweigerd')
            onToggle(false)
        }
    },
}
