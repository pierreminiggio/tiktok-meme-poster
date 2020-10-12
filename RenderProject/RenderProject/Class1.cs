using System;
using System.Collections.Generic;
using System.IO;
using System.Runtime.InteropServices.ComTypes;
using System.Text;
using System.Threading.Tasks;

using System.Windows.Forms;
using ScriptPortal.Vegas;

namespace RenderProject
{
    public class EntryPoint
    {
        public void FromVegas(Vegas myVegas)
        {
            try
            {
                myVegas.NewProject();
                myVegas.Project.Video.Height = 2920;
                myVegas.Project.Video.Width = 1080;

                VideoTrack videoTrack = new VideoTrack(myVegas.Project, 0, "Meme");
                myVegas.Project.Tracks.Add(videoTrack);

                AudioTrack audioTrack = new AudioTrack(myVegas.Project, 1, "Meme Music");
                myVegas.Project.Tracks.Add(audioTrack);

                string audioPath = "F:\\dev\\tiktok-meme-poster\\server\\public\\musiques\\coffin-dance-9.mp3";
                int duration = 10000;

                AudioEvent audioEvent = (AudioEvent)AddMedia(
                    myVegas.Project,
                    audioPath,
                    1,
                    Timecode.FromSeconds(0),
                    Timecode.FromMilliseconds(10000)
                );

                Timecode cursorPosition = myVegas.Transport.CursorPosition;
                string imagePath = "F:\\dev\\tiktok-meme-poster\\server\\public\\images\\1_sized.png";
                VideoEvent imageEvent = (VideoEvent)AddMedia(
                    myVegas.Project,
                    imagePath,
                    0,
                    cursorPosition,
                    Timecode.FromMilliseconds(10000)
                );
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message);
            }
        }

        TrackEvent AddMedia(
            Project project,
            string mediaPath,
            int trackIndex,
            Timecode start,
            Timecode length
        )
        {
            Media media = Media.CreateInstance(project, mediaPath);
            Track track = project.Tracks[trackIndex];

            if (track.MediaType == MediaType.Video)
            {
                VideoTrack videoTrack = (VideoTrack)track;
                VideoEvent videoEvent = videoTrack.AddVideoEvent(start, length);
                Take take = videoEvent.AddTake(media.GetVideoStreamByIndex(0));

                return videoEvent;
            }
            else if (track.MediaType == MediaType.Audio)
            {
                AudioTrack audioTrack = (AudioTrack)track;
                AudioEvent audioEvent = audioTrack.AddAudioEvent(start, length);
                Take take = audioEvent.AddTake(media.GetAudioStreamByIndex(0));

                return audioEvent;
            }
            
            // Should be impossible
            return null;
        }
    }
}

