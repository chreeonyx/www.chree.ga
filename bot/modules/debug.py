import json
from getopt import getopt

from lib.cog import Cog, event
from lib.command import makeCommand, Command
from lib.objects import User, Status, HandleChange, Message, JumpinError
from lib.styling import Colors, Styles, encodetxt




class Debug(Cog):
    async def get_template(self):
        f = open('./docs/help_template.md', 'r')
        text = f.read()
        command_data = ""

        for cog in self.bot.cm.cogs.values():
            command_data += f"-----{cog.name}------\n"
            for command in cog.commands:
                command_data += f"{getattr(command, '__command_name__')}: {getattr(command, '__description__')}\n"
            command_data += "\n"
        text = text.format(commands=command_data)
        f.close()
        return text

    # @makeCommand(aliases=["t", description="test")
    # async def generate_readme(self, c: Command):
    #     generated = await self.get_template()
    #     f = open('./README.md', 'w')
    #     f.write(generated)
    #     f.flush()
    #     f.close()


    @makeCommand(aliases=["me", "you"], description="t")
    async def thirdperson(self, c: Command):
        await self.send_action(c.message)

    @makeCommand(aliases=["mock"], description="mocks a socket message.")
    async def mocking_bird(self, c: Command):
        await self.bot._recv(c.message)



    @makeCommand(aliases=["font"], description="")
    async def demofonts(self, c: Command):
        fontstyles = {
            "bold": encodetxt("bold", Styles.bold),
            "italic": encodetxt("italic", Styles.italic),
            "bolditalic": encodetxt("bolditalic", Styles.bolditalic),
            "bubble": encodetxt("bubble", Styles.bubble),
            "bubbleinvert": encodetxt("bubbleinvert", Styles.bubbleinvert),
            "square": encodetxt("square", Styles.square),
            "squareinvert": encodetxt("squareinvert", Styles.squareinvert),
            "script": encodetxt("script", Styles.script)
        }
        if len(c.message) < 2:
            a_ = []
            for each in fontstyles.keys():
                a_.append(fontstyles[each])
            await self.send_message(", ".join(a_))
        else:
            parts = c.message.split(" ")
            type_ = parts[0]
            message = " ".join(parts[1:])
            if type_ in Styles.__dict__.keys():
                formated = encodetxt(message, Styles.__dict__[type_])
            else:
                formated = encodetxt(c.message, Styles.script)
            await self.send_message(formated)

    @makeCommand(aliases=["exception"], description="raises an exception")
    async def testit(self, c: Command):
        raise Exception("I am a T-Rex")

    #####
    # Events
    #####
    @event(event="client::error")
    async def error(self, error: JumpinError):
        print(error.__dict__)
        # does not work if bot is guest in  a room with authenticated required.

        if error.message:
            #await self.send_message(f"{error.context}:{error.error}:{error.message}")
            pass
        else:
            #await self.send_message(f"{error.context}:{error.error}")
            pass


    @event(event="room::message")
    async def message(self, message: Message):
        print(message.message)
        pass


