import re

import aiohttp

from lib.cog import Cog
from lib.command import Command, makeCommand


class api(Cog):
    do_url = "http://api.banon.cf/v1/do/{}"
    play_url = "http://api.banon.cf/v1/play/{}"
    create_url = "http://api.banon.cf/v1/create/{}"
    read_url = "http://api.banon.cf/v1/read/{}"
    update_url = "http://api.banon.cf/v1/update/{}"
    delete_url = "http://api.banon.cf/v1/delete/{}"

    async def endpoint(self, ep, query: str) -> str:
        async with aiohttp.ClientSession() as session:
            async with session.get(ep.format(query)) as response:
                if response.status != 200:
                    definition = f"The Banon's API Status {response.status}"
                else:
                    try:
                        urbjson = await response.json()
                        _definition = urbjson["response"]
                        definition = re.sub(r"\[*\]*", "", _definition).replace("\n", " ").replace("\r", " ")
                    except (IndexError, KeyError):
                        definition = f"Couldn't find anything for {query}"
                return definition

    @makeCommand(aliases=["do"], description="API v1 /do Endpoint")
    async def api_do(self, c: Command):
        if len(c.message) == 0:
            response = "Need a term to lookup"
        else:
            response = await self.endpoint(self.do_url,c.message)
        await self.send_message(response)
        
    @makeCommand(aliases=["play"], description="API v1 /play Endpoint")
    async def api_play(self, c: Command):
        if len(c.message) == 0:
            response = "Need a term to lookup"
        else:
            response = await self.endpoint(self.play_url,c.message)
        await self.send_message(response)

    @makeCommand(aliases=["create"], description="API v1 /create Endpoint")
    async def api_create(self, c: Command):
        if len(c.message) == 0:
            response = "Need a term to lookup"
        else:
            response = await self.endpoint(self.create_url,c.message)
        await self.send_message(response)

    @makeCommand(aliases=["read"], description="API v1 /read Endpoint")
    async def api_read(self, c: Command):
        if len(c.message) == 0:
            response = "Need a term to lookup"
        else:
            response = await self.endpoint(self.read_url,c.message)
        await self.send_message(response)

    @makeCommand(aliases=["update"], description="API v1 /update Endpoint")
    async def api_update(self, c: Command):
        if len(c.message) == 0:
            response = "Need a term to lookup"
        else:
            response = await self.endpoint(self.update_url,c.message)
        await self.send_message(response)

    @makeCommand(aliases=["delete"], description="API v1 /delete Endpoint")
    async def api_delete(self, c: Command):
        if len(c.message) == 0:
            response = "Need a term to lookup"
        else:
            response = await self.endpoint(self.delete_url,c.message)
        await self.send_message(response)
