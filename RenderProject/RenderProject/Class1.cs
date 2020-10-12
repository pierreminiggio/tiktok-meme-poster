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
            string configString = File.ReadAllText("./tmp.csv");
            string[] configArray = configString.Split(';');

            try
            {
                myVegas.NewProject();
                myVegas.Project.Video.Height = 1920;
                myVegas.Project.Video.Width = 1080;

                VideoTrack videoTrack = new VideoTrack(myVegas.Project, 0, "Meme");
                myVegas.Project.Tracks.Add(videoTrack);

                AudioTrack audioTrack = new AudioTrack(myVegas.Project, 1, "Meme Music");
                myVegas.Project.Tracks.Add(audioTrack);

                string audioPath = configArray[1];
                int duration = int.Parse(configArray[2]);

                AudioEvent audioEvent = (AudioEvent)AddMedia(
                    myVegas.Project,
                    audioPath,
                    1,
                    Timecode.FromSeconds(0),
                    Timecode.FromMilliseconds(duration)
                );

                Timecode cursorPosition = myVegas.Transport.CursorPosition;
                string imagePath = configArray[0];
                VideoEvent imageEvent = (VideoEvent)AddMedia(
                    myVegas.Project,
                    imagePath,
                    0,
                    cursorPosition,
                    Timecode.FromMilliseconds(duration)
                );

                myVegas.SaveProject(configArray[3]);

                if (renderProject(myVegas, configArray[4]))
                {
                    // Success
                    myVegas.Exit();
                }
                else
                {
                    // Failed
                    myVegas.Exit();
                }
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

        private bool renderProject(
            Vegas myVegas,
            string outputFilePath
        )
        {
            RenderArgs renderArgs = new RenderArgs(myVegas.Project);
            renderArgs.RenderTemplate = findTemplate(myVegas.Renderers);
            renderArgs.OutputFile = outputFilePath;
            RenderStatus renderStatus = myVegas.Render(renderArgs);
            if (renderStatus == RenderStatus.Complete)
            {
                return true;
            }

            return false;
        }

        private RenderTemplate findTemplate(Renderers renderers)
        {
            foreach (Renderer renderer in renderers)
            {
                if (renderer.FileTypeName == "MAGIX AVC/AAC MP4")
                {
                    foreach (RenderTemplate template in renderer.Templates)
                    {
                        if (template.Name == "Modèle par défaut")
                        {
                            return template;
                        }
                    }
                }
            }

            throw new Exception("Template not found");
        }
    }
}

